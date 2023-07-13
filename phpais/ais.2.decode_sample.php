<?php
// Include the AIS encoder/decoder
include_once('ais.2.php');

class MyAIS extends AIS {
	// This function is Overridable and is called by process_ais_itu(...) method
	function decode_ais($_aisdata) {
		$ro = new stdClass(); // return object
		$ro->cls = 0; // AIS class undefined, also indicate unparsed msg
		$ro->name = '';
		$ro->sog = -1.0;
		$ro->cog = 0.0;
		$ro->lon = 0.0;
		$ro->lat = 0.0;
		$ro->ts = time();
		$ro->id = bindec(substr($_aisdata,0,6));
		$ro->mmsi = bindec(substr($_aisdata,8,30));
		if ($ro->id >= 1 && $ro->id <= 3) {
			$ro->cog = bindec(substr($_aisdata,116,12))/10;
			$ro->sog = bindec(substr($_aisdata,50,10))/10;
			$ro->lon = $this->make_lonf(bindec(substr($_aisdata,61,28)));
			$ro->lat = $this->make_latf(bindec(substr($_aisdata,89,27)));
			$ro->cls = 1; // class A
		}
		else if ($ro->id == 5) {
			//$imo = bindec(substr($_aisdata,40,30));
			//$cs = $this->binchar($_aisdata,70,42);
			$ro->name = $this->binchar($_aisdata,112,120);
			$ro->cls = 1; // class A
		}
		else if ($ro->id == 18) {
			$ro->cog = bindec(substr($_aisdata,112,12))/10;
			$ro->sog = bindec(substr($_aisdata,46,10))/10;
			$ro->lon = $this->make_lonf(bindec(substr($_aisdata,57,28)));
			$ro->lat = $this->make_latf(bindec(substr($_aisdata,85,27)));
			$ro->cls = 2; // class B
		}
		else if ($ro->id == 19) {
			$ro->cog = bindec(substr($_aisdata,112,12))/10;
			$ro->sog = bindec(substr($_aisdata,46,10))/10;
			$ro->lon = $this->make_lonf(bindec(substr($_aisdata,61,28)));
			$ro->lat = $this->make_latf(bindec(substr($_aisdata,89,27)));
			$ro->name = $this->binchar($_aisdata,143,120);
			$ro->cls = 2; // class B
		}
		else if ($ro->id == 24) {
			$pn = bindec(substr($_aisdata,38,2));
			if ($pn == 0) {
				$ro->name = $this->binchar($_aisdata,40,120);
			}
			$ro->cls = 2; // class B
		}
		var_dump($ro); // dump results here for demo purpose
		return $ro;
	}
}

$ais = new MyAIS();

// Test Single Message
if (1) {
	$buf = "!AIVDM,1,1,,A,15DAB600017IlR<0e2SVCC4008Rv,0*64\r\n";
	// Important Note:
	// After receiving input from incoming serial or TCP/IP, call the process_ais_buf(...) method and pass in
	// the input from device for further processing.
	$ais->process_ais_buf($buf);
}

// Test With Large Array Of Messages - represent packets of incoming data from serial port or IP connection
if (1) {
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
		// Important Note:
		// After receiving input from incoming serial or TCP/IP, call the process_ais_buf(...) method and pass in
		// the input from device for further processing.
		$ais->process_ais_buf($test2_1);
	}
}

?>
