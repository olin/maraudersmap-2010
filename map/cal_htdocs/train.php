<?

require('lib_common.php');
require('lib_macdata.php');
require('lib_train.php');

DBConnect();

$placename = $_GET['placename'];
if (!$placename) exit;

$data = $_GET['data'];
if (!$data) exit;

$username = $_GET['username'];

$mapx = (int) $_GET['mapx'];
$mapy = (int) $_GET['mapy'];
$mapw = (int) $_GET['mapw'];
if (!$mapx || !$mapy || !$mapw) { echo 'did not recieve position.'; exit;}
$platform = $_GET['platform']; //optional for backwards compat.

train($username, $placename, $data, $mapx, $mapy, $mapw, false, $platform);

// insert the position as well
require('lib_position.php');
notifyPosition($username, $strStatus, $placename);

echo 'success:'.rand()

?>
