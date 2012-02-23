<?php

// figure out data that is interesting
require('lib_common.php');
DBConnect();

set_time_limit(10000);

// start with time of day data

// count the number of hits per hour

// get data for each hour

$hours = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23);
//$hours = array(0, 1);

foreach ($hours as $hour)
{
	$q = "SELECT COUNT(*) FROM data WHERE HOUR(date) = " . $hour . " AND page='/map/update.php'";

	list($ret,$n) = DB($q);
	
	while ($result = mysql_fetch_array($ret))
	{		echo $hour . ": " . $result[0];
	}
	echo "<br><br>";
//	$bins[$hour] = //get count
}


?>
