<?php
class iCal { 

	var $folders;
	
	function iCal() {
		$this->folders = 'cals';
	}
	
	function iCalList() {
		if ( $handle = opendir( $this->folders ) ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				$files[] = $file;
			}
			return array_filter($files, array($this,"iCalClean"));
		}
	}
	
	function iCalClean($file) {
			return strpos($file, '.ics');
	}
	
	function iCalReader() {
		$array = $this->iCalList();
		foreach ($array as $icalfile) {
			$iCaltoArray[$icalfile] = $this->iCalDecoder($icalfile);
		}
		return $iCaltoArray;
	}
	
	function iCalDecoder($file) {
		$ical = file_get_contents('cals/'.$file);
		preg_match_all('/(BEGIN:VEVENT.*?END:VEVENT)/si', $ical, $result, PREG_PATTERN_ORDER);
		for ($i = 0; $i < count($result[0]); $i++) {
			$tmpbyline = explode("\r\n", $result[0][$i]);
			
			foreach ($tmpbyline as $item) {
				$tmpholderarray = explode(":",$item);
				if (count($tmpholderarray) >1) { 
					$majorarray[$tmpholderarray[0]] = $tmpholderarray[1];
				}
				
			}
			/*
				lets just finish what we started..
			*/
			if (preg_match('/DESCRIPTION:(.*)END:VEVENT/si', $result[0][$i], $regs)) {
				$majorarray['DESCRIPTION'] = str_replace("  ", " ", str_replace("\r\n", "", $regs[1]));
			} 
			$icalarray[] = $majorarray;
			unset($majorarray);
			 
			 
		}
		return $icalarray;
	}
	
}

$ical = new iCal();
//print_r( $ical->iCalReader() );
$cals = $ical->iCalReader();
foreach ($cals as $calendar => $calArray)
{
	echo $calendar . "<hr>";
	foreach ($calArray as $eventNumber => $eventArray)
	{
		$location = "";
		$description = "";
		foreach ($eventArray as $key => $value)
		{
			if (stripos($key, "SUMMARY") !== false)
			{
				// this is the description
				$description = $value;
			}
			if (stripos($key, "LOCATION") !== false)
			{
				// this is a location
				$location = $value;
			}
			if (stripos($key, "DTSTART") !== false)
			{
				if (($startTime = strtotime($value)) === false) {
					echo "The string ($value) is bogus";
				}
				
			}
			if (stripos($key, "DTEND") !== false)
			{
				if (($endTime = strtotime($value)) === false) {
					echo "The string ($value) is bogus";
				}
				
			}
//			echo $key . "<br>";
		}
		echo $description . " in <b>" . $location . "</b> at <i>" . date('l dS \o\f F Y h:i:s A', $startTime) . "</i> until <i>" . date('l dS \o\f F Y h:i:s A', $endTime) . "</i><br>";
	}
}

?>

