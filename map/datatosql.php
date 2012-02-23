<?php
echo "disabled";
exit();
echo "RUNNING...";
require('lib_common.php');
DBConnect();

set_time_limit(1000);

// date/time	page	place	user	string

$filename = "fullReal2.dat";
$counter = 0;
$handle = @fopen($filename, "r");
if ($handle) {
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		// $buffer is now a line in the file -- parse it
//		preg_match("/username=([a-zA-Z0-9 _-]*)&platform/", $buffer, $matches);
//		$username = $matches[1];

		preg_match("/GET ([A-Za-z\/\._0-9]*)/", $buffer, $matches2);
		$page = $matches2[1];
		
		preg_match("/placename=[^& ]*/", $buffer, $matches3);
		$place = $matches3[0];
		$place = str_replace("placename=", "", $place);
		
		preg_match("/username=[^& ]*/", $buffer, $matches4);
		$username = $matches4[0];
		$username = str_replace("username=", "", $username);
		
		preg_match("/\[[^\]]*\]/", $buffer, $matches5);
		$timeStr = $matches5[0];
		$timeStr = str_replace("[", "", $timeStr);
		$timeStr = str_replace("]", "", $timeStr);
		
		$timestamp = strtotime($timeStr);
		if ($timestamp === false)
		{

		} else {
			// now insert all the data into the SQL database
			$q = "INSERT INTO data VALUES (FROM_UNIXTIME(" . $timestamp . "),'" . mysql_real_escape_string($page) . "','" . mysql_real_escape_string($place) . "','" . mysql_real_escape_string($username) . "','" . mysql_real_escape_string($buffer) . "')";

			list($ret,$n) = DB($q);
			
			$counter ++;

			if ($counter % 10000 == 0)
			{
				echo ".";
			}
			if ($counter % 500000 == 0)
			{
				echo "<br>";
			}
		}

	}
	fclose($handle);
} else {
	echo "Failed to open file.";
}

?>

