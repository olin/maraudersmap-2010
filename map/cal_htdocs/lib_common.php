<?
require 'db_connection.php';

# This project requires a file called db_connection.php
# That file should contain a function called DBConnect that creates a mysql database connection.

function DBFix( $str, $killspaces = false, $lenlimit = true)
{
	if ($killspaces) $str = str_replace(' ','',$str);
	if ($lenlimit && strlen($str)>10000) {die('Database query is too long.');} //Too long.
	return mysql_real_escape_string($str); // We could also use the less good add slashes
}
function EchoFix($str)
{
	//Kludge: magic quotes is being annoying:
	if (get_magic_quotes_gpc()) $str = stripslashes($str);

	return strip_tags(htmlspecialchars($str));
}
function DB($query, $bres=true)
{
	$result = mysql_query($query) or DEBUG('Error.'.mysql_error());
	//If it is a Insert or Delete query, $result will be true
	if ($result===true || $result===false) return $result;
	
	$nmax = mysql_num_rows($result);
	return array($result , $nmax);
}
function DEBUG($str)
{
echo '<font color="red">'.$str.'</font>';
exit;
}

?>