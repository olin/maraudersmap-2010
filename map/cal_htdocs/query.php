<?
require('lib_common.php');
require('lib_macdata.php');
require('lib_position.php');

DBConnect();

$name = $_GET['username'];
if (!$name) exit;

$res = queryPosition($name);
echo 'success:'.$res;

?>
