<?
require('lib_common.php');
require('lib_pointexistence.php');

DBConnect();

$placename = $_GET['placename'];
if (!$placename) exit;

$res = pointexistence($placename);
if ($res)
	echo 'success:true';
else
	echo 'success:false';
?>
