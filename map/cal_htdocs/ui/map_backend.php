<?
// We don't want caching!
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Pragma: no-cache'); 

require('../lib_common.php');
require_once('usernames.php');

DBConnect();

// This is data
$mapw = (int) $_GET['mapw'];
if (! $mapw) {echo 'no map'; exit;}


$debugShowHistory = false;
$historyInterval = 604800; //in seconds -- this is a week

if ($debugShowHistory)
$q = 'select usercal.username, pointcal.mapx, pointcal.mapy, pointcal.placename from usercal inner join pointcal on usercal.placename=pointcal.placename where (unix_timestamp(NOW()) - unix_timestamp(usercal.lastupdated)<'.$historyInterval.') and pointcal.mapw='.$mapw;
else
$q = 'select usercal.username, pointcal.mapx, pointcal.mapy, pointcal.placename, usercal.lastupdated, usercal.icon from usercal inner join pointcal on usercal.placename=pointcal.placename where pointcal.mapw='.$mapw . ' and (unix_timestamp(NOW()) - unix_timestamp(usercal.lastupdated)<'.$historyInterval.') order by usercal.lastupdated DESC';

//~ $q = 'select usercal.username, pointcal.mapx, pointcal.mapy, pointcal.placename from usercal inner join point on usercal.placename=pointcal.placename where pointcal.mapw='.$mapw;
list($res, $n) = DB($q);

$astr=array();
$usernames = array(); //Hash table containing usernames that have been used.
while ($arr = mysql_fetch_array($res))
{
	if (!$debugShowHistory)
	{
		if ($usernames[$arr['username']]) continue; //We have already recorded a point from that user. No duplicates.
		$usernames[$arr['username']] = 1;
	}
	$temp = ParseLocation($arr['placename']);
	$location = $temp[0];
	$floor = $temp[1];
	$usernameValue = $usernameArray[$arr['username']];
	if ($usernameValue == "")
	{
		$usernameValue = $arr['username'];
	}
	$icon = $arr['icon'];
	
	if ($icon == "")
	{
		//default icon
		$icon = "p.gif";
	} else
	{
		$icon = "../../" . $icon;
	}
	array_push($astr, $arr['mapx'].'|'.$arr['mapy'].'|'.$usernameValue.'|'.$location.'|'.$arr['lastupdated'].'|'.$floor.'|'.$icon);
}
$str = implode(';',$astr);

echo 'success:' . $str;

function ParseLocation($location)
{
	
	// location strings look like WH,in,rm309
	$location = str_replace("OC", "MH", $location);
	$tempArray = explode(",", $location);
	$building = $tempArray[0];
	$inside = $tempArray[1];
	$description = $tempArray[2];
	
	$floorNum = substr($building, 2, 1);
	$building = substr($building, 0, 2);
	
	if ($inside == "in")
	{
		$inside = "inside";
	} elseif ($inside == "out")
	{
		$inside = "outside of";
	}

	if ($floorNum == "1")
	{
		$floor = "1st";
	} elseif ($floorNum == "2")
	{
		$floor = "2nd";
	} elseif ($floorNum == "3")
	{
		$floor = "3rd";
	} elseif ($floorNum == "4")
	{
		$floor = "4th";
	} elseif ($floorNum == "0")
	{
		$floor = "LL";
	}

	if (stripos($description, "rm") !== false || stripos($description, "room") !== false)
	{
		// this has a room
		$description = str_ireplace("rm", "", $description);
		$description = str_ireplace("room", "", $description);

		$location = $inside . " " . $building . $description;
	} else
	{
		if ($floor != "LL")
		{
			$location = $inside . " " . $building . " " . $floor . " floor " . $description;
		} else
		{
			$location = $inside . " " . $building . " (" . $floor . ") " . $description;
		}
	}
	
	$ret[0] = ucfirst($location);
	$ret[1] = $floorNum;
	return $ret;
}

function ParseUsername($username, $usernameArray)
{
	//change a username like "abarry" to "Andy Barry"
	
}

?>
