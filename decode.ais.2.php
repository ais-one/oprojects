<?php
/*
A more simplified way of AIS ITU decoding
? Receives a broadcast message,
? Organises the binary bits of the Message Data into 6-bit strings,
? Converts the 6-bit strings into their representative "valid characters" – see IEC 61162-1, table 7,
? Assembles the valid characters into an encapsulation string, and
? Transfers the encapsulation string using the VDM sentence formatter.
*/
$ais = "15MgK45P3@G?fl0E`JbR0OwT0@MS";
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

$ais = "55?MbV02;H;s<HtKR20EHE:0@T4@Dn2222222216L961O5Gf0NSQEp6ClRp888888888880";
/*
AIVDM,2,1,1,A,55?MbV02;H;s<HtKR20EHE:0@T4@Dn2222222216L961O5Gf0NSQEp6ClRp8,0*1C
AIVDM,2,2,1,A,88888888880,2*25
User ID	351759000	
AIS Version	0	Station compliant with AIS Edition 0
IMO Number	9134270	
Call Sign	3FOF8 	
Name	EVER DIADEM 	
*/

$aisdata168=NULL;//six bit array of ascii characters

function ascii_2_dec($chr) {
	$dec=ord($chr);//get decimal ascii code
	$hex=dechex($dec);//convert decimal to hex
	return ($dec);
}

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
echo $aisdata168 . "<br/>";

echo "mmsi= " . bindec(substr($aisdata168,8,30)) . "<br/>";
echo "imo= " . bindec(substr($aisdata168,40,30)) . "<br/>";
echo "cs= " . binchar($aisdata168,70,42) . "<br/>";
echo "name= " . binchar($aisdata168,112,120) . "<br/>";
//echo "cog= " . bindec(substr($aisdata168,116,12))/10 . "<br/>";
//echo "sog= " . bindec(substr($aisdata168,50,10))/10 . "<br/>";

