<?
// We don't want caching!
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Pragma: no-cache'); 

require('../lib_common.php');
DBConnect();


// This is data
$mapw = (int) $_GET['mapw'];
if (! $mapw) {echo 'no map'; exit;}


$debugShowHistory = false;
$historyInterval = 6000; //in seconds

if ($debugShowHistory)
$q = 'select users.username, point.mapx, point.mapy, point.placename from users inner join point on users.placename=point.placename where (unix_timestamp(NOW()) - unix_timestamp(users.lastupdated)<'.$historyInterval.') and point.mapw='.$mapw;
else
$q = 'select users.username, point.mapx, point.mapy, point.placename, users.lastupdated from users inner join point on users.placename=point.placename where point.mapw='.$mapw . ' and (unix_timestamp(NOW()) - unix_timestamp(users.lastupdated)<'.$historyInterval.') order by users.lastupdated DESC';

//~ $q = 'select users.username, point.mapx, point.mapy, point.placename from users inner join point on users.placename=point.placename where point.mapw='.$mapw;
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
	array_push($astr, $arr['mapx'].'|'.$arr['mapy'].'|'.$arr['username'].'|'.$arr['placename'].'|'.$arr['lastupdated']);
}
$str = implode(';',$astr);

echo 'success:' . $str;
?>