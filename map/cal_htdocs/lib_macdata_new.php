<?
global $macToId;

// range for wireless MAC address

// $macToId['00:0B:0E:11:AC'] = 9;	#OLIN_EH
// goes to 11:AC:BF -- anything within that range is that AP

// each access point has two anntennas -- 1 is b/g and the other is 802.11a

// some people do have 802.11a issues

$macToId = array();
$macToId['x0'] = 0;	#OLIN_EH
$macToId['00:0B:0E:11:9B'] = 1;	#OLIN_EH
$macToId['00:0B:0E:11:82'] = 2;	#OLIN_EH
$macToId['00:0B:0E:11:EB'] = 3;	#OLIN_EH
$macToId['00:0B:0E:11:8C'] = 4;	#OLIN_EH
$macToId['00:0B:0E:11:8B'] = 5;	#OLIN_EH
$macToId['00:0B:0E:11:CE'] = 6;	#OLIN_EH
//$macToId['00:0B:0E:11:8A'] = 7;	#OLIN_EH
$macToId['xx:xx:xx:xx:07'] = 7;	#OLIN_EH
$macToId['00:0B:0E:11:CC'] = 8;	#OLIN_EH is the same as 37 FAIL
$macToId['00:0B:0E:11:AC'] = 9;	#OLIN_EH
$macToId['00:20:D8:28:74'] = 10;	#OLIN_EH
//$macToId['00:0B:0E:11:96'] = 11;	#OLIN_EH 11 is the same as 36 FIXED
$macToId['xx:xx:xx:xx:11'] = 11;	#OLIN_EH 11 is the same as 36 FIXED
$macToId['00:0B:0E:11:CD'] = 12;	#OLIN_EH
$macToId['00:0B:0E:11:83'] = 13;	#OLIN_EH
$macToId['00:0B:0E:11:8A'] = 14;	#OLIN_EH 14 is the same as 7 FIXED
$macToId['00:0B:0E:11:58'] = 15;	#OLIN_EH
$macToId['00:0B:0E:11:7E'] = 16;	#OLIN_EH
$macToId['00:15:E8:E1:CF'] = 17;	#OLIN_WH
$macToId['00:15:E8:E3:D7'] = 18;	#OLIN_WH
$macToId['00:15:E8:E3:DF'] = 19;	#OLIN_WH
$macToId['00:20:D8:28:0E'] = 20;	#OLIN_WH
$macToId['00:20:D8:28:7A'] = 21;	#OLIN_WH
$macToId['00:20:D8:28:8F'] = 22;	#OLIN_WH
$macToId['00:20:D8:28:A8'] = 23;	#OLIN_WH
$macToId['00:20:D8:2D:27'] = 24;	#OLIN_WH
$macToId['00:20:D8:2D:35'] = 25;	#OLIN_WH
$macToId['00:20:D8:2D:3A'] = 26;	#OLIN_WH
$macToId['00:20:D8:2D:65'] = 27;	#OLIN_WH
$macToId['00:20:D8:2D:8D'] = 28;	#OLIN_WH
$macToId['00:20:D8:2D:8F'] = 29;	#OLIN_WH
$macToId['00:20:D8:2D:9E'] = 30;	#OLIN_WH
$macToId['00:20:D8:2D:C0'] = 31;	#OLIN_WH
$macToId['00:20:D8:2D:35'] = 32;	#OLIN_WH
$macToId['00:0B:0E:11:F2'] = 33;	#OLIN_AC
$macToId['00:0B:0E:11:91'] = 34;	#OLIN_AC
$macToId['00:0B:0E:11:8E'] = 35;	#OLIN_AC
$macToId['00:0B:0E:11:96'] = 36;	#OLIN_AC
$macToId['00:0B:0E:11:CC'] = 37;	#OLIN_AC
$macToId['00:0B:0E:11:ED'] = 38;	#OLIN_AC
$macToId['00:0B:0E:11:8F'] = 39;	#OLIN_AC
$macToId['00:0B:0E:11:93'] = 40;	#OLIN_AC
$macToId['00:0B:0E:11:95'] = 41;	#OLIN_AC
$macToId['00:0B:0E:12:49'] = 42;	#OLIN_AC
$macToId['00:0B:0E:11:92'] = 43;	#OLIN_AC
$macToId['00:0B:0E:11:9D'] = 44;	#OLIN_AC
$macToId['00:0B:0E:11:88'] = 45;	#OLIN_AC
//$macToId['00:0B:0E:11:95'] = 46;	#OLIN_AC // #46 and 41 are the same FIXED???
$macToId['xx:xx:xx:xx:46'] = 46;	#OLIN_AC // #46 and 41 are the same FIXED???
$macToId['00:0B:0E:12:46'] = 47;	#OLIN_AC
$macToId['00:0B:0E:11:98'] = 48;	#OLIN_AC
$macToId['00:20:D8:2D:45'] = 49;	#OLIN_OC
$macToId['00:20:D8:2D:31'] = 50;	#OLIN_OC
$macToId['00:20:D8:2D:84'] = 51;	#OLIN_OC
$macToId['00:15:E8:D7:44'] = 52;	#OLIN_OC
$macToId['00:20:D8:2D:B2'] = 53;	#OLIN_OC
$macToId['00:20:D8:2D:B8'] = 54;	#OLIN_OC
$macToId['00:20:D8:2D:89'] = 55;	#OLIN_OC
$macToId['00:20:D8:2C:BB'] = 56;	#OLIN_OC
$macToId['00:20:D8:2C:C4'] = 57;	#OLIN_OC
$macToId['00:20:D8:2D:57'] = 58;	#OLIN_OC
$macToId['00:15:E8:E1:44'] = 59;	#OLIN_OC
$macToId['00:20:D8:2D:54'] = 60;	#OLIN_OC
$macToId['00:20:D8:2D:42'] = 61;	#OLIN_OC
$macToId['00:20:D8:2D:43'] = 62;	#OLIN_OC
$macToId['00:0B:0E:0F:A7'] = 63;	#OLIN_OC
$macToId['00:20:D8:2D:61'] = 64;	#OLIN_OC
$macToId['00:20:D8:2D:B3'] = 65;	#OLIN_CC
$macToId['00:20:D8:2D:63'] = 66;	#OLIN_CC
$macToId['00:15:E8:E3:75'] = 67;	#OLIN_CC
$macToId['00:20:D8:2D:B6'] = 68;	#OLIN_CC
$macToId['00:20:D8:2D:85'] = 69;	#OLIN_CC
$macToId['00:20:D8:2D:2C'] = 70;	#OLIN_CC
$macToId['00:20:D8:2D:64'] = 71;	#OLIN_CC
$macToId['00:20:D8:2D:B7'] = 72;	#OLIN_CC
//$macToId['00:20:D8:2D:42'] = 73;      #OLIN_CC
$macToId['xx:xx:xx:xx:xx'] = 73;      #OLIN_CC
$macToId['x1'] = 74;                         
$macToId['x2'] = 75;
$macToId['x3'] = 76;


function macToId($mac)
{

	global $macToId;
	$fullMac = $mac;
	// some AP have the same last two digits
	if ($mac == "00:20:D8:2D:42:C3")
	{
		$ret = 73;
	} elseif ($mac == "00:0B:0E:11:96:C2")
	{
		$ret = 11;
	} elseif ($mac == "00:0B:0E:11:8A:43")
		$ret = 7;
	} elseif ($mac == "00:0B:0E:11:95:C2") // ???DOUBLE CHECK THIS ONE
		$ret = 46;
	} else {
		$mac = str($mac, 0, 14);
		$ret = $macToId[$mac];
	}
	if ($ret)
	{
		return $ret;
	} elseif ($ret===0)
	{
		return 0;
		// log errors
		error_log("\nFailed MAC address: " . $fullMac, 3, "/raid0/www/map/mac_errors.log");
	} else
	{
		error_log("\nFailed MAC address: " . $fullMac, 3, "/raid0/www/map/mac_errors.log");
		return -1;
	}
}

function countTotalMacs()
{
	global $macToId;
	return count($macToId);
}


?>
