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
function ais2char($ais_str, $_len, $bit_start_pos) { // bit size from _len
	GLOBAL $ais_unmap;
	$buf = "";
	for ($i=0; $i<$_len; $i++) {
		$_tval = (int)ais2int( $ais_str, 6, $bit_start_pos + ($i * 6) ); // int
		$tbuf = $ais_unmap[ $_tval ]; // TBD potential for out of index range error
		//if ($tbuf == ',') $tbuf = ':'; // to prevent problems with CSV
		$buf = $buf.$tbuf;
	}
	return $buf; // char *
}

?>




