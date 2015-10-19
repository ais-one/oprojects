<?php

// Include the AIS encoder/decoder
include_once('ais.2.php');

$ais = new AIS();

// Test Single Message
if (1) {
	$buf = "!AIVDM,1,1,,A,15DAB600017IlR<0e2SVCC4008Rv,0*64\r\n";
	$o = $ais->process_ais_buf($buf);
	var_dump($o);
}

// Test With Large Array Of Messages - represent packets of incoming data from serial port or IP connection
if (0) {
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
		$o = $ais->process_ais_buf($test2_1);
		var_dump($o);
	}
}

?>
