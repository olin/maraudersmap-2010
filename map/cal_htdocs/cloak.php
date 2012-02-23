<?
require('lib_common.php');

DBConnect();

$name = $_GET['username'];
if (!$name) exit;

$q = 'UPDATE usercal SET placename="cloaked" WHERE username="'.DBFix($name).'"';

$res = DB($q);

echo 'success:'.$res;

?>
