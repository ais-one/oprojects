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

// unsigned long
function make_latf($temp) {
	$flat; // float
	$temp = $temp & 0x07FFFFFF;
	if ($temp & 0x04000000)
	{
		$temp = $temp ^ 0x07FFFFFF;
		$temp += 1;
		$flat = (float)($temp / (60.0 * 10000.0));
		$flat *= -1.0;
	}
	else
	{
		$flat = (float)($temp / (60.0 * 10000.0));
	}
	return $flat; // float
}

// unsigned long
function make_lonf($temp) {
	$flon; // float
	$temp = $temp & 0x0FFFFFFF;
	if ($temp & 0x08000000)
	{
		$temp = $temp ^ 0x0FFFFFFF;
		$temp += 1;
		$flon = (float)($temp / (60.0 * 10000.0));
		$flon *= -1.0;
	}
	else
	{
		$flon = (float)($temp / (60.0 * 10000.0));
	}
	return $flon;
}

// map of 6-bit AIS STRING char values to 8-bit ascii values
$ais_map = array(
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   0,   1,
    2,   3,   4,   5,   6,   7,   8,   9,  10,  11,
   12,  13,  14,  15,  16,  17,  18,  19,  20,  21,
   22,  23,  24,  25,  26,  27,  28,  29,  30,  31,
   32,  33,  34,  35,  36,  37,  38,  39,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  40,  41,  42,  43,
   44,  45,  46,  47,  48,  49,  50,  51,  52,  53,
   54,  55,  56,  57,  58,  59,  60,  61,  62,  63,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,

   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1
); // char 256

// map of 8-bit ascii values to 6-bit AIS STRING char values
$ais_map64 = array(
   '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
   ':', ';', '<', '=', '>', '?', '@', 'A', 'B', 'C',
   'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
   'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
   '`', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
   'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
   't', 'u', 'v', 'w'
); // char 64

// AIS text 8-bit ascii to 6-bit ascii
$ais_rev_unmap = array(
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  32,  33,  34,  35,  36,  37,  38,  39,
   40,  41,  42,  43,  44,  45,  46,  47,  48,  49,
   50,  51,  52,  53,  54,  55,  56,  57,  -1,  -1,
   -1,  -1,  -1,  -1,   0,   1,   2,   3,   4,   5,
    6,   7,   8,   9,  10,  11,  12,  13,  14,  15,
   16,  17,  18,  19,  20,  21,  22,  23,  24,  25,
   26,  27,  28,  29,  30,  31,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,

   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1
); // char 256

// AIS text 6-bit ascii to 8-bit ascii
$ais_unmap = array(
//  ' ' --- '?', // 0x20 - 0x3F
//  '@' --- '_', // 0x40 - 0x5F
  '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I',
  'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
  'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '[', '\\', ']',
  '^', '_', ' ', '!', '\"', '#', '$', '%', '&', '\'',
  '(', ')', '*', '+', ',', '-', '.', '/', '0', '1',
  '2', '3', '4', '5', '6', '7', '8', '9', ':', ';',
  '<', '=', '>', '?',  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,

   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1,   1,
   -1,  -1,  -1,  -1,  -1,  -1,  -1,  -1
); // char 256

// char*, int, int
function form_hex_data($ais_str, $_num_bits, $bit_start_pos) {
	$bytes = $_num_bits / 8; // int
	$remainder = $_num_bits % 8; // int
	if ($remainder != 0) $bytes = $bytes + 1;

	for ($i=0; $i<$bytes; $i++) // int
	{
		$val; // unsigned char
		if ( $remainder && ($i == ($bytes -1)) )
			$val = ais2int($ais_str, $remainder, $bit_start_pos + ($i * 8) ); // unsigned char
		else
			$val = ais2int($ais_str, 8, $bit_start_pos + ($i * 8) ); // unsigned char
	}
	return false; // TBD pack and return hexstring?
}

// decoding
// char*, int, int
function ais2int($ais_str, $bit_size, $bit_start_pos) {
	GLOBAL $ais_map;
$BYTE6_SIZE = 6; // int

	$val = 0; // int
	$num_byte6 = (int)($bit_size / 6); // int
	$remainder = $bit_size % 6; // int

	$start_byte6 = (int)($bit_start_pos / 6); // int
	$start_bit6 = $bit_start_pos % 6; // int

	if ($remainder)
	{
		$num_byte6+=1;
		if ( ($BYTE6_SIZE - $start_bit6) < $remainder )
			$num_byte6+=1;
	}
	else
	{
		if ($start_bit6 != 0) $num_byte6+=1;
	}

	// do the translation now...
	if ($num_byte6 > 1)
	{
		$num_first_bit = -1; // int
		$num_last_bit = -1; // int

		$num_first_bit = $BYTE6_SIZE - $start_bit6;
		$num_last_bit = $bit_size - (($num_byte6 - 2) * $BYTE6_SIZE) - $num_first_bit;

		$temp; // int
		for ($i = ($num_byte6-1); $i>=0; $i--)
		{
			//echo "1>".($start_byte6 + $i)." ".(int)$ais_str[$start_byte6 + $i]."\n";
			$xx = (int)$ais_map[ $ais_str[$start_byte6 + $i] ]; // unsigned char
			//echo "2>".(int)$xx."\n";

			if ($i == ($num_byte6-1) )
			{
				$xx = $xx >> (6 - $num_last_bit);
				$temp = (int)$xx;
				//DEBUG echo "a";
			}
			else if ( $i == 0 )
			{
				//DEBUG echo "int> ".(int)($start_bit6 + 2)." ";
				$xx = $xx << ($start_bit6 + 2);
				//DEBUG echo "b0 $xx ";
				$xx = $xx & 0x000000ff;
				//DEBUG echo "b1 $xx ";
				$xx = $xx >> ($start_bit6 + 2);
				//DEBUG echo "b2 $xx ";
				$temp = (int)$xx;
				//DEBUG echo "b3 $xx ";
				$temp = $temp << (( ($num_byte6-2) * 6) + $num_last_bit);
				//DEBUG echo "b4 $temp ";
				//toremove? $temp = $temp >> $start_bit6;
			}
			else
			{
				$temp = (int)$xx;
				$temp = $temp << (( (($num_byte6-2) - $i) * 6) + $num_last_bit);
				//DEBUG echo "c";
			}
			$val = $val | (int)$temp;
		}
	}
	else {
		$shl = ($start_bit6 + 2); // int
		$xx = $ais_map [ $ais_str[$start_byte6] ] << $shl; // unsigned char
		$xx = $xx & 0x000000ff;
		$shr = $shl + (8 - $shl - $bit_size); // int
		$xx = $xx >> $shr;
		$val = (int)$xx;
		//DEBUG echo "d";
	}

	//DEBUG echo ">>".$val."\n";
	return $val; // return int
}

// char*, int, int
function ais2char($ais_str, $_len, $bit_start_pos) {
	GLOBAL $ais_unmap;
	// bit size from _len
	$buf = ""; // static char 129
	for ($i=0; $i<$_len; $i++)
	{
		$_tval = (int)ais2int( $ais_str, 6, $bit_start_pos + ($i * 6) ); // int
		$tbuf = $ais_unmap[ /* (char) */ $_tval ]; // TBD potential for crash
		if ($tbuf == ',') $tbuf = ':'; // to prevent problems with CSV
		$buf = $buf.$tbuf;
	}
	return $buf; // char *
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

	if ( $chksum == $dcs ) // NMEA checksum pass
	{
		$pcs = explode(',', $rawdata);
		// !AI??? identifier
		$num_seq = (int)$pcs[1]; // number of sequences
		$seq = (int)$pcs[2]; // get sequence

		// get msg sequence id
		if ($pcs[3] == '') // non-multipart message, set to -1
		{
			$msg_sid = -1;
		}
		else // multipart message
		{
			$msg_sid = (int)$pcs[3];
		}
		$ais_ch = $pcs[4]; // get AIS channel

		// message sequence checking
		if ($num_seq < 1 || $num_seq > 9)
		{
			echo "ERROR,INVALID_NUMBER_OF_SEQUENCES ".time()." $rawdata\n";
			return -1;
		}
		else if ($seq < 1 || $seq > 9)
		{ // invalid sequences number
			echo "ERROR,INVALID_SEQUENCES_NUMBER ".time()." $rawdata\n";
			return -1;
		}
		else if ($seq > $num_seq)
		{
			echo "ERROR,INVALID_SEQUENCE_NUMBER_OR_INVALID_NUMBER_OF_SEQUENCES ".time()." $rawdata\n";
			return -1;
		}
		else
		{ // sequencing ok, handle single/multi-part messaging
			if ($seq == 1) // always init to 0 at first sequence
			{
				$filler = 0; // ?
				$itu = ""; // init message length
				$pseq = 0; // note previous sequence number
				$cmsg_sid = $msg_sid; // note msg_sid
			}
			if ($num_seq > 1) // for multipart messages
			{
				if ($cmsg_sid != $msg_sid // different msg_sid
					|| $msg_sid == -1 // invalid initial msg_sid
					|| ($seq - $pseq) != 1 // not insequence
					)
				{  // invalid for multipart message
					$msg_sid = -1;
					$cmsg_sid = -1;
					echo "ERROR,INVALID_MULTIPART_MESSAGE ".time()." $rawdata\n";
					return -1;
				}
				else 
				{
					$pseq++;
				}
			}

			$itu = $itu.$pcs[5]; // get itu message
			$filler += (int)$pcs[6][0]; // get filler

			if ($num_seq == 1 // valid single message
				|| $num_seq == $pseq // valid multi-part message
				)
			{
				if ($num_seq != 1) // test
				{
					//echo $rawdata;
				}
				return process_ais_itu($itu, strlen($itu), $filler /*, $ais_ch*/);
			}
		} // end process raw AIS string (checksum passed)
	}
	return -1;
}


function process_ais_itu($_itu, $_len, $_filler /*, $ais_ch*/) {
	GLOBAL $port; // tcpip port...
	static $debug_counter = 0;
	//DEBUG echo $_itu."\n";
	$x_a = array();
	for ($i = 0; $i<$_len; $i++) $x_a[] = ord($_itu[$i]); // convert string to array bytes

	//$debug_counter=$debug_counter+1;
	//echo ">>>> $debug_counter\n";
	$id = (int)ais2int($x_a, 6, 0); // msg id
	//echo $id."\n";
	$mmsi = 0;
	$name = '';
	$sog = -1.0;
	$cog = 0.0;
	$lon = 0.0;
	$lat = 0.0;
	$cls = 0; // class undefined
	if ($id >= 1 && $id <= 3) {
		$mmsi = ais2int($x_a, 30, 8); // mmsi
		$lon = make_lonf( ais2int($x_a, 28, 61) ); // lon
		$lat = make_latf( ais2int($x_a, 27, 89) ); // lat
		$sog = (float)ais2int($x_a, 10, 50) / 10.0; // sog
		$cog = (float)ais2int($x_a, 12, 116) / 10.0; // cog
		//$hdg = ais2int($x_a, 9, 128); // hdg
		$cls = 1; // class A
	}
	else if ($id == 18) {
		$mmsi = ais2int($x_a, 30, 8); // mmsi
		$lon = make_lonf( ais2int($x_a, 28, 57) ); // lon
		$lat = make_latf( ais2int($x_a, 27, 85) ); // lat
		$sog = (float)ais2int($x_a, 10, 46) / 10.0; // sog
		$cog = (float)ais2int($x_a, 12, 112) / 10.0; // cog
		//$hdg = ais2int($x_a, 9, 124); // hdg
		$cls = 2; // class B
	}
	else if ($id == 19) {
		$mmsi = ais2int($x_a, 30, 8); // mmsi
		$lon = make_lonf( ais2int($x_a, 28, 61) ); // lon
		$lat = make_latf( ais2int($x_a, 27, 89) ); // lat
		$sog = (float)ais2int($x_a, 10, 46) / 10.0; // sog
		$cog = (float)ais2int($x_a, 12, 112) / 10.0; // cog
		//$hdg = ais2int($x_a, 9, 124); // hdg
		$name = ais2char($x_a, 20, 143); // name
		$name = str_replace ( '@' , '', $name ); // sanitize name...
		$cls = 2; // class B
	}
	else if ($id == 5) {
		$mmsi = ais2int($x_a, 30, 8); // mmsi
		//echo ais2int($x_a, 30, 40)."\n"; // IMO Number
		//echo ais2char($x_a, 7, 70)."\n"; // callsign
		$name = ais2char($x_a, 20, 112); // name
		$name = str_replace ( '@' , '', $name ); // sanitize name...
		$cls = 1; // class A
	}
	else if ($id == 24) {
		$mmsi = ais2int($x_a, 30, 8); // mmsi
		$pn = ais2int($x_a, 2, 38); // mmsi
		if ($pn == 0)
		{
			$name = ais2char($x_a, 20, 40); // name
			$name = str_replace ( '@' , '', $name ); // sanitize name...
		}
		$cls = 2; // class B
	}
	if ($mmsi > 0 && $mmsi<1000000000) {// valid mmsi only...
		$utc = time();
		echo "$mmsi, $name, $utc, $lon, $lat, $sog, $cog, $cls, $port<br>\n";
	}
	return $id;
}

function process_ais_buf($ibuf) // from serial or IP comms
{
	static $cbuf = "";
	
	$cbuf = $cbuf.$ibuf;

	$last_pos = 0;
	while ( ($start = strpos($cbuf,"VDM",$last_pos)) !== FALSE)
	//while ( ($start = strpos($cbuf,"!AI",$last_pos)) !== FALSE)
	{
		//DEBUG echo $cbuf;
		if ( ($end = strpos($cbuf,"\r\n", $start)) !== FALSE) //TBD need to trim?
		{
			$tst = substr($cbuf, $start - 3, ($end - $start + 3));
			//DEBUG echo "[$start $end $tst]\n";
			process_ais_raw( $tst );
			$last_pos = $end + 1;
		}
		else
		{
			break;
		}
	}
	
	if ($last_pos > 0) $cbuf = substr($cbuf, $last_pos); // move...
	if (strlen($cbuf) > 1024) $cbuf = ""; // prevent overflow simple mode...
}


/*

//array of characters convert to ord...
//$xstr = "15DAB600017IlR<0e2SVCC4008Rv";
////1243814390;
//$xstr_a = "!AIVDM,1,1,,A,15DAB600017IlR<0e2SVCC4008Rv,0*64";

$xstr = "55RiwV02>3bLS=HJ220t<D4r0<u84j222222221?=PD?55Pf0BTjCQhD3lQH88888888880";
////1243814400;
$xstr_a = "!AIVDM,2,1,8,A,55RiwV02>3bLS=HJ220t<D4r0<u84j222222221?=PD?55Pf0BTjCQhD,0*73";
$xstr_b = "!AIVDM,2,2,8,A,3lQH88888888880,2*6A";

//$xstr = "15DAB600017IlR<0e2SVCC4008Rv";
//$xstr = "55RiwV02>3bLS=HJ220t<D4r0<u84j222222221?=PD?55Pf0BTjCQhD3lQH88888888880";
//$xstr = "58KWe:02@<5KUH<kB208hu=<tn2222222222221@;@I995AE0=PCVAD`2CQ3kQDj@H88880";
//$xstr = "53kttoP2>U<H=4t:220i0Nt>05@h5>1=@5:2221@8pA<35Mg0<ljCQhD3lQH88888888880";



$buf = "55RiwV02>3bLS=HJ220t<D4r0<u84j222222221?=PD?55Pf0BTjCQhD3lQH88888888880";
////1243814400;
$xstr_a = "!AIVDM,2,1,8,A,55RiwV02>3bLS=HJ220t<D4r0<u84j222222221?=PD?55Pf0BTjCQhD,0*73";
$xstr_b = "!AIVDM,2,2,8,A,3lQH88888888880,2*6A";
*/

// Test Message 1
$buf = "!AIVDM,1,1,,A,15DAB600017IlR<0e2SVCC4008Rv,0*64\r\n";
process_ais_buf($buf);


// Test With Large Array
$test2_a = array( "sdfdsf!AIVDM,1,1,,B,18JfEB0P007Lcq00gPAdv?v000Sa,0*21\r\n!AIVDM,1,1,,B,18Jjr@00017Kn",
	"jh0gNRtaHH00@06,0*37\r\n!AI","VDM,1,1,,B,18JTd60P017Kh<D0g405cOv00L<c,0*",
	"42\r\n",
	"!AIVDM,2,1,8,A,55RiwV02>3bLS=HJ220t<D4r0<u84j222222221?=PD?55Pf0BTjCQhD,0*73\r\n",
	"!AIVDM,2,2,8,A,3lQH888888",
	"88880,2*6A\r",
	"\n!AIVDM,2,1,9,A,569w5`02>0V090=V221@DpN0<PV222222222221EC8S@:5O`0B4jCQhD,0*11\r\n!AIVDM,2,2,9,A,3lQH88888888880,2*6B\r\n!AIVDO,1,1,",
	",A,D05GdR1MdffpuTf9H0,4*7","E\r\n!AIVDM,1,1,,A,?","8KWpp0kCm2PD00,2*6C\r\n!AIVDM,1,1,,A,?8KWpp1Cf15PD00,2*3B\r\nUIIII"
);

foreach ($test2_a as $test2_1) {
	process_ais_buf($test2_1);
}

?>




