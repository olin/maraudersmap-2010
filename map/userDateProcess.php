<?php
include("userDateMaxClean.php");
include("userDateClean.php");

// figure out the length of time of users

foreach ($userArray as $key => $value)
{
	/*
	key: abarry
	Value:
	
	Array
	(
		[max] => 2009-02-02 14:06:15
		[min] => 2008-03-01 04:36:54
	)
	*/
	
	echo $key . " >> " . subtract_dates($value["min"], $value["max"]) . "<br>";
}

// count how many people are on at a given time
$startDate = strtotime("2008-01-01");
//echo strtotime("2008-03-01");
//echo "<br>";
$thisDate = $startDate;
$endDate = strtotime("2009-03-01");
while ($thisDate < $endDate)
{
	//echo $thisDate . " ";
	$count = 0;
	// count to see how many are here now
	foreach ($userArray as $key => $value)
	{
//		if (strtotime($value["min"]) <= $thisDate && strtotime($value["max"]) > $thisDate)
		if (strtotime($value["min"]) <= $thisDate)
		{
			$count ++;
		}
	}
	$dateArray[$thisDate] = $count;
	
	//echo $thisDate;
	// we want the day users joined, not the day after, so subtract one day
//	echo (date("z", $thisDate - 86400) + 36date("Y", $thisDate - 86400) - 2008) . ", " . $count . "<br>";
	if (date("Y", $thisDate - 86400) == "2009")
	{
//		echo date("z", $thisDate - 86400) + 367 . ", " . $count . "<br>";
	} else {
//		echo date("z", $thisDate - 86400) + 1 . ", " . $count . "<br>";
	}
	
	$thisDate = date(date("U", $thisDate) + 86400);
}


#
//date format: yyyy-mm-dd 
//$date_diff = subtract_dates("2007-11-5", "2007-11-20"); 
//echo $date_diff . " days"; 
function subtract_dates($begin_date, $end_date) 
{ 
	return round(((strtotime($end_date) - strtotime($begin_date)) / 86400)); 
}
  

?>
