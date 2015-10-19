<?php
include_once('ais.2.php');

$ais = new AIS();

// An Example Of Generating Message 24
$enc = '';
$enc .= str_pad(decbin(24), 6, '0', STR_PAD_LEFT);
$enc .= str_pad(decbin(0), 2, '0', STR_PAD_LEFT);
$enc .= str_pad(decbin(351759000), 30, '0', STR_PAD_LEFT);
$enc .= str_pad(decbin(0), 2, '0', STR_PAD_LEFT);
$enc .= $ais->char2bin('ASIAN JADE', 20);

//Test Code
//echo $enc.'<br/>';
//echo "id= " . bindec(substr($enc,0,6)) . "<br/>";
//echo "mmsi= " . bindec(substr($enc,8,30)) . "<br/>";
//echo "name= " . binchar($enc,40,120) . "<br/>";

// WARNING: it may not appear correct if displayed on browser due to the '<' character.
// Use browser view source and the full AIS string will be shown.
echo $ais->mk_ais($enc).'<br/>';

?>
