<?
require('lib_common.php');
require('lib_macdata.php');
require('lib_position.php');
require('lib_lookup.php');

DBConnect();

// Does 2 things: Send in old position and check new position!


$username = $_GET['username'];
if (!$username) {echo 'nousername'; exit;}

$placename = $_GET['placename'];
$status = $_GET['status'];
if ($placename || $status)
{
	// Record in the database where this user was
	notifyPosition($username, $status, $placename);
}

$minTime = strtotime($_GET['mintime']);
$maxTime = strtotime($_GET['maxtime']);

if (!$minTime)
{
    $minTime = mktime(0, 0, 0, 12, 32, 1997);
}

if (!$maxTime)
{
    $maxTime = mktime(0, 0, 0, 12, 32, 2020);
}

$data = $_GET['data'];
if (!$data) {echo 'No Olin Wireless Found'; exit;}

$platform = $_GET['platform'];
$results = lookup(strtoupper($data),false, $platform, $minTime, $maxTime);

echo 'success:'.$results;

?>

