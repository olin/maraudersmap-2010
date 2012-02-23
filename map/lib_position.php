<?

function notifyPosition($strName, $strStatus, $strPlacename)
{
	// Don't leave the older ones - get rid of them!
	
	//make sure to get the icon name before deleting!
	$q = 'SELECT icon FROM users WHERE username="' . DBFix($strName) . '"';
	list($res, $n) = DB($q);
	
	if ($n > 0)
	{
		$row = mysql_fetch_array($res);
		$icon = $row['icon'];
	} else
	{
		$icon = "";
	}
	
	
	//This could probably be done better by an update query, but this works too.
	$q = 'delete from users where username="'.DBFix($strName).'"';
	DB($q);
	
	
	$q = 'insert into users (username, placename, status, lastupdated, icon) values ("'.DBFix($strName).'","'.DBFix($strPlacename).'","'.DBFix($strStatus).'", NOW(), "' . $icon . '")';
	return DB($q);
}

function queryPosition($strName)
{
	$q = 'select username, placename, status, lastupdated from users where username LIKE "%'.DBFix($strName).'%" order by lastupdated DESC limit 1';
	list($ret,$n) = DB($q);
	if ($n)
	{
	$result =  mysql_fetch_array($ret);
	return $result['username'].'|'.$result['placename'].'|'.$result['status'].'|'.$result['lastupdated'];
	}
	else return 'nobody';
}

?>
