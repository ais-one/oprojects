<?php
/*
Copyright 2014 Aaron Gong Hsien-Joen <aaronjxz@gmail.com>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

class AIS {
/* AIS Decoding
- Receive and get ITU payload
- Organises the binary bits of the Payload into 6-bit strings,
- Converts the 6-bit strings into their representative "valid characters" – see IEC 61162-1, table 7,
- Assembles the valid characters into an encapsulation string, and
- Transfers the encapsulation string using the VDM sentence formatter.
*/
	function make_latf($temp) { // unsigned long 
		$flat; // float
		$temp = $temp & 0x07FFFFFF;
		if ($temp & 0x04000000) {
			$temp = $temp ^ 0x07FFFFFF;
			$temp += 1;
			$flat = (float)($temp / (60.0 * 10000.0));
			$flat *= -1.0;
		}
		else $flat = (float)($temp / (60.0 * 10000.0));
		return $flat; // float
	}

	function make_lonf($temp) { // unsigned long
		$flon; // float
		$temp = $temp & 0x0FFFFFFF;
		if ($temp & 0x08000000) {
			$temp = $temp ^ 0x0FFFFFFF;
			$temp += 1;
			$flon = (float)($temp / (60.0 * 10000.0));
			$flon *= -1.0;
		}
		else $flon = (float)($temp / (60.0 * 10000.0));
		return $flon;
	}

	function ascii_2_dec($chr) {
		$dec=ord($chr);//get decimal ascii code
		$hex=dechex($dec);//convert decimal to hex
		return ($dec);
	}
	/*
	$ais_map64 = array(
	   '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', // 48
	   ':', ';', '<', '=', '>', '?', '@', 'A', 'B', 'C',
	   'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
	   'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', // 87
	   '`', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', // 96
	   'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
	   't', 'u', 'v', 'w' // 119
	); // char 64
	*/
	function asciidec_2_8bit($ascii) {
		//only process in the following range: 48-87, 96-119
		if ($ascii < 48) { }
		else {
			if($ascii>119) { }
			else {
				if ($ascii>87 && $ascii<96) ;
				else {
					$ascii=$ascii+40;
					if ($ascii>128){$ascii=$ascii+32;}
					else{$ascii=$ascii+40;}
				}
			}
		}
		return ($ascii);
	}

	function dec_2_6bit($dec) {
		$bin=decbin($dec);
		return(substr($bin, -6)); 
	}

	function binchar($_str, $_start, $_size) {
		//  ' ' --- '?', // 0x20 - 0x3F
		//  '@' --- '_', // 0x40 - 0x5F
		$ais_chars = array(
			'@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
			'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
			'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', '\\', ']',
			'^', '_', ' ', '!', '\"', '#', '$', '%', '&', '\'',
			'(', ')', '*', '+', ',', '-', '.', '/', '0', '1',
			'2', '3', '4', '5', '6', '7', '8', '9', ':', ';',
			'<', '=', '>', '?'
		);
		$rv = '';
		if ($_size % 6 == 0) {
			$len = $_size / 6;
			for ($i=0; $i<$len; $i++) {
				$offset = $i * 6;
				$rv .= $ais_chars[ bindec(substr($_str,$_start + $offset,6)) ];
			}
		}
		return $rv;
	}

	// This function is Overidable
	// function for decoding the AIS Message ITU Payload
	function decode_ais($_aisdata) {
	}

	function process_ais_itu($_itu, $_len, $_filler /*, $ais_ch*/) {
		GLOBAL $port; // tcpip port...
		static $debug_counter = 0;
		
		$aisdata168='';//six bit array of ascii characters

		$ais_nmea_array = str_split($_itu); // convert to an array
		foreach ($ais_nmea_array as $value) {
			$dec = $this->ascii_2_dec($value);
			$bit8 = $this->asciidec_2_8bit($dec);
			$bit6 = $this->dec_2_6bit($bit8);
			//echo $value ."-" .$bit6 ."";
			$aisdata168 .=$bit6;
		}
		//echo $aisdata168 . "<br/>";
		$this->decode_ais($aisdata168);
	}

	// char* - AIS \r terminated string
	function process_ais_raw($rawdata) { // return int
		static $num_seq; // 1 to 9
		static $seq; // 1 to 9
		static $pseq; // previous seq

		static $msg_sid = -1; // 0 to 9, indicate -1 at start state of device, do not process messages
		static $cmsg_sid; // current msg_sid
		static $itu; // buffer for ITU message

		$filler = 0; // fill bits (int)
		$chksum = 0;

		// raw data without the \n

		// calculate checksum after ! till *
		// assume 1st ! is valid
		
		// find * ensure that it is at correct position
		$end = strrpos ( $rawdata , '*' );
		if ($end === FALSE) return -1; // check for NULLS!!!
		
		$cs = substr( $rawdata, $end + 1 );
		if ( strlen($cs) != 2 ) return -1; // correct cs length
		$dcs = (int)hexdec( $cs );
		
		for ( $alias=1; $alias<$end; $alias++) $chksum ^= ord( $rawdata[$alias] ); // perform XOR for NMEA checksum

		if ( $chksum == $dcs ) { // NMEA checksum pass
			$pcs = explode(',', $rawdata);
			// !AI??? identifier
			$num_seq = (int)$pcs[1]; // number of sequences
			$seq = (int)$pcs[2]; // get sequence

			// get msg sequence id
			if ($pcs[3] == '') $msg_sid = -1; // non-multipart message, set to -1
			else $msg_sid = (int)$pcs[3]; // multipart message
			$ais_ch = $pcs[4]; // get AIS channel

			// message sequence checking
			if ($num_seq < 1 || $num_seq > 9) {
				echo "ERROR,INVALID_NUMBER_OF_SEQUENCES ".time()." $rawdata\n";
				return -1;
			}
			else if ($seq < 1 || $seq > 9) { // invalid sequences number
				echo "ERROR,INVALID_SEQUENCES_NUMBER ".time()." $rawdata\n";
				return -1;
			}
			else if ($seq > $num_seq) {
				echo "ERROR,INVALID_SEQUENCE_NUMBER_OR_INVALID_NUMBER_OF_SEQUENCES ".time()." $rawdata\n";
				return -1;
			}
			else { // sequencing ok, handle single/multi-part messaging
				if ($seq == 1) { // always init to 0 at first sequence
					$filler = 0; // ?
					$itu = ""; // init message length
					$pseq = 0; // note previous sequence number
					$cmsg_sid = $msg_sid; // note msg_sid
				}
				if ($num_seq > 1) { // for multipart messages
					if ($cmsg_sid != $msg_sid // different msg_sid
						|| $msg_sid == -1 // invalid initial msg_sid
						|| ($seq - $pseq) != 1 // not insequence
						) {  // invalid for multipart message
						$msg_sid = -1;
						$cmsg_sid = -1;
						echo "ERROR,INVALID_MULTIPART_MESSAGE ".time()." $rawdata\n";
						return -1;
					}
					else {
						$pseq++;
					}
				}

				$itu = $itu.$pcs[5]; // get itu message
				$filler += (int)$pcs[6][0]; // get filler

				if ($num_seq == 1 // valid single message
					|| $num_seq == $pseq // valid multi-part message
					) {
					if ($num_seq != 1) { // test
						//echo $rawdata;
					}
					return $this->process_ais_itu($itu, strlen($itu), $filler /*, $ais_ch*/);
				}
			} // end process raw AIS string (checksum passed)
		}
		return -1;
	}

	// incoming data from serial or IP comms
	function process_ais_buf($ibuf) {
		static $cbuf = "";
		$cbuf = $cbuf.$ibuf;
		$last_pos = 0;
		while ( ($start = strpos($cbuf,"VDM",$last_pos)) !== FALSE) {
		//while ( ($start = strpos($cbuf,"!AI",$last_pos)) !== FALSE) {
			//DEBUG echo $cbuf;
			if ( ($end = strpos($cbuf,"\r\n", $start)) !== FALSE) { //TBD need to trim?
				$tst = substr($cbuf, $start - 3, ($end - $start + 3));
				//DEBUG echo "[$start $end $tst]\n";
				$this->process_ais_raw( $tst );
				$last_pos = $end + 1;
			}
			else break;
		}
		if ($last_pos > 0) $cbuf = substr($cbuf, $last_pos); // move...
		if (strlen($cbuf) > 1024) $cbuf = ""; // prevent overflow simple mode...
	}

/* AIS Encoding
*/
	function mk_ais_lat( $lat ) {
		//$lat = 1.2569;
		if ($lat<0.0) {
			$lat = -$lat;
			$neg=true;
		}
		else $neg=false;
		$latd = 0x00000000;
		$latd = intval ($lat * 600000.0);
		if ($neg==true) {
			$latd = ~$latd;
			$latd+=1;
			$latd &= 0x07FFFFFF;
		}
		return $latd;
	}

	function mk_ais_lon( $lon ) {
		//$lon = 103.851;
		if ($lon<0.0) {
			$lon = -$lon;
			$neg=true;
		}
		else $neg=false;
		$lond = 0x00000000;
		$lond = intval ($lon * 600000.0);
		if ($neg==true) {
			$lond = ~$lond;
			$lond+=1;
			$lond &= 0x0FFFFFFF;
		}
		return $lond;
	}

	function char2bin($name, $max_len) {
		$len = strlen($name);
		if ($len > $max_len) $name = substr($name,0,$max_len);
		if ($len < $max_len) $pad = str_repeat('0', ($max_len - $len) * 6);
		else $pad = '';
		$rv = '';
		$ais_chars = array(
			'@'=>0, 'A'=>1, 'B'=>2, 'C'=>3, 'D'=>4, 'E'=>5, 'F'=>6, 'G'=>7, 'H'=>8, 'I'=>9,
			'J'=>10, 'K'=>11, 'L'=>12, 'M'=>13, 'N'=>14, 'O'=>15, 'P'=>16, 'Q'=>17, 'R'=>18, 'S'=>19,
			'T'=>20, 'U'=>21, 'V'=>22, 'W'=>23, 'X'=>24, 'Y'=>25, 'Z'=>26, '['=>27, '\\'=>28, ']'=>29,
			'^'=>30, '_'=>31, ' '=>32, '!'=>33, '\"'=>34, '#'=>35, '$'=>36, '%'=>37, '&'=>38, '\''=>39,
			'('=>40, ')'=>41, '*'=>42, '+'=>43, ','=>44, '-'=>45, '.'=>46, '/'=>47, '0'=>48, '1'=>49,
			'2'=>50, '3'=>51, '4'=>52, '5'=>53, '6'=>54, '7'=>55, '8'=>56, '9'=>57, ':'=>58, ';'=>59,
			'<'=>60, '='=>61, '>'=>62, '?'=>63
		);
		$_a = str_split($name);
		if ($_a) foreach ($_a as $_1) {
			if (isset($ais_chars[$_1])) $dec = $ais_chars[$_1];
			else $dec = 0;
			$bin = str_pad(decbin( $dec ), 6, '0', STR_PAD_LEFT);
			$rv .= $bin;
			//echo "$_1 $dec ($bin)<br/>";
		}
		return $rv.$pad;
	}

	function mk_ais($_enc, $_part=1,$_total=1,$_seq='',$_ch='A') {
		$len_bit = strlen($_enc);
		$rem6 = $len_bit % 6;
		$pad6_len = 0;
		if ($rem6) $pad6_len = 6 - $rem6;
		//echo  $pad6_len.'<br>';
		$_enc .= str_repeat("0", $pad6_len); // pad the text...
		$len_enc = strlen($_enc) / 6;
		//echo $_enc.' '.$len_enc.'<br/>';

		$itu = '';

		for ($i=0; $i<$len_enc; $i++) {
			$offset = $i * 6;
			$dec = bindec(substr($_enc,$offset,6));
			if ($dec < 40) $dec += 48;
			else $dec += 56;
			//echo chr($dec)." $dec<br/>";
			$itu .= chr($dec);
		}

		// add checksum
		$chksum = 0;
		$itu = "AIVDM,$_part,$_total,$_seq,$_ch,".$itu.",0";

		$len_itu = strlen($itu);
		for ($i=0; $i<$len_itu; $i++) {
			$chksum ^= ord( $itu[$i] );
		}

		$hex_arr = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
		$lsb = $chksum & 0x0F;
		if ($lsb >=0 && $lsb <= 15 ) $lsbc = $hex_arr[$lsb];
		else $lsbc = '0';
		$msb = (($chksum & 0xF0) >> 4) & 0x0F;
		if ($msb >=0 && $msb <= 15 ) $msbc = $hex_arr[$msb];
		else $msbc = '0';

		$itu = '!'.$itu."*{$msbc}{$lsbc}\r\n";
		return $itu;
	}
}
?>
