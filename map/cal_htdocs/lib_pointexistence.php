<?

function pointexistence($placename)
{
	$q = 'select placename from pointcal where placename = "'. DBFix($placename).'"';
	list($ret,$n) = DB($q);
	if ($n)
		return true;
	else
		return false;
}


?>
