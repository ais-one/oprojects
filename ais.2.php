<?php
/*

1. Decode the ITU Payload

2. Encode to full AIS string

3. Todo:
a) decoder for 1,2,3,5,17,18,24A for now
b) sample output message 24A

Receives a broadcast message,
Organises the binary bits of the Message Data into 6-bit strings,
Converts the 6-bit strings into their representative "valid characters" – see IEC 61162-1, table 7,
Assembles the valid characters into an encapsulation string, and
Transfers the encapsulation string using the VDM sentence formatter.
*/
$ais = '15MgK45P3@G?fl0E`JbR0OwT0@MS';
/*
User ID	366730000	
Navigation Status	5	Moored
Rate of Turn (ROT)	-729	
Speed Over Ground (SOG)	20.8	
Position Accuracy	0	An unaugmented GNSS fix with accuracy > 10 m
Longitude	-122.392531666667	West
Latitude	37.8038033333333	North
Course Over Ground (COG)	51.3	
True Heading (HDG)	511	Not available (default)
*/

$ais = '55?MbV02;H;s<HtKR20EHE:0@T4@Dn2222222216L961O5Gf0NSQEp6ClRp888888888880';
/*
AIVDM,2,1,1,A,55?MbV02;H;s<HtKR20EHE:0@T4@Dn2222222216L961O5Gf0NSQEp6ClRp8,0*1C
AIVDM,2,2,1,A,88888888880,2*25
User ID	351759000	
AIS Version	0	Station compliant with AIS Edition 0
IMO Number	9134270	
Call Sign	3FOF8 	
Name	EVER DIADEM 	
*/

$ais = 'H5?MbV05<T4r0`4@D0000000000';

$aisdata168=NULL;//six bit array of ascii characters


function make_latf($temp) {
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

// unsigned long
function make_lonf($temp) {
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
 
$ais_nmea_array = str_split($ais); // convert to an array
foreach ($ais_nmea_array as $value) {
	$dec=ascii_2_dec($value);
	$bit8=asciidec_2_8bit($dec);
	$bit6=dec_2_6bit($bit8);
	//echo $value ."-" .$bit6 ."";
	$aisdata168 .=$bit6;
}
//echo $aisdata168 . "<br/>";

echo "id= " . bindec(substr($aisdata168,0,6)) . "<br/>";
echo "mmsi= " . bindec(substr($aisdata168,8,30)) . "<br/>";

//echo "imo= " . bindec(substr($aisdata168,40,30)) . "<br/>";
//echo "cs= " . binchar($aisdata168,70,42) . "<br/>";
//echo "name= " . binchar($aisdata168,112,120) . "<br/>";

//echo "cog= " . bindec(substr($aisdata168,116,12))/10 . "<br/>";
//echo "sog= " . bindec(substr($aisdata168,50,10))/10 . "<br/>";

echo "name= " . binchar($aisdata168,40,120) . "<br/>";


/*
Message ID 6 Identifier for Message 24; always 24
Repeat indicator 2 Used by the repeater to indicate how many times a message has been
repeated. 0 = default; 3 = do not repeat any more
User ID 30 MMSI number
Part number 2 Identifier for the message part number; always 0 for Part A
Name 120 Name of the MMSI-registered vessel. Maximum 20 characters 6-bit
ASCII, @@@@@@@@@@@@@@@@@@@@ = not
available = default
Number of bits 160 Occupies one-time period 
*/
echo '<hr/>';

/*

Encoding

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
	if ($neg==true)
	{
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
	if ($neg==true)
	{
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

$enc = '';
$enc .= str_pad(decbin(24), 6, '0', STR_PAD_LEFT);
$enc .= str_pad(decbin(0), 2, '0', STR_PAD_LEFT);
$enc .= str_pad(decbin(351759000), 30, '0', STR_PAD_LEFT);
$enc .= str_pad(decbin(0), 2, '0', STR_PAD_LEFT);
$enc .= char2bin('ASIAN JADE', 20);

//echo $enc.'<br/>';
//echo "id= " . bindec(substr($enc,0,6)) . "<br/>";
//echo "mmsi= " . bindec(substr($enc,8,30)) . "<br/>";
//echo "name= " . binchar($enc,40,120) . "<br/>";

function make_itu($_enc, $_part=1,$_total=1,$_seq='',$_ch='A') {
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
	$itu = "AIVDM,$_part,$_total,$_seq,$_ch,".$itu;

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

	$itu = '!'.$itu.',0'
		."*{$msbc}{$lsbc}\r\n";
	return $itu;
}

echo make_itu($enc).'<br/>';
