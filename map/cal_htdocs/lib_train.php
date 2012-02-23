<?

function createVector($n) { $ret = array(); for ($i=0; $i<$n; $i++) $ret[$i] = 0; return $ret;}

// add data point to space
function train($username, $name, $data, $mapx, $mapy, $mapw, $bDebug=false, $platform, $datetime)
{
	if (!$platform) $platform = 'win'; //for backwards compat.
	if ($platform=='win') $nplatform = 0;
	elseif ($platform=='linux') $nplatform = 1;
	elseif ($platform=='mac') $nplatform = 2;
	else $nplatform = 90;

	$nTotalMacs = countTotalMacs();
	
	// Vector with 76 + 1 components
	$vector = createVector($nTotalMacs);
	
	$bSomedatapoints = false;
	
	// Parse input data. Incoming data is in format MAC,STRENGTH;MAC,STRENGTH;
	$ardata = explode(';', $data);
	for ($i=0; $i<count($ardata); $i++)
	{
		$cdata = explode(',',$ardata[$i]);
		$strMac = $cdata[0];
		$fStrength = (float) $cdata[1];
		
		$nSpot = macToId($strMac);
		if ($nSpot==-1)
		{
			if ($bDebug) echo 'Unknown mac-'.$strMac; 
			continue;
		}
		$bSomedatapoints = true;
		$vector[$nSpot] = $fStrength;
	}
	if ($bDebug) print_r($vector);
	
	if (!$bSomedatapoints)
	{
		//~ if ($bDebug) 
		echo 'Error: no valid mac addresses.';
		return;
	}
	
	DBConnect();
	
	// sometimes points get added to the system that are already in there
	// check to see if that is the case and make their pixel values the same
	
	$q = 'SELECT * FROM pointcal WHERE placename = "'.DBFix($name).'"';
	
	$result = DB($q);
	
	if ($result[1] >= 1)
	{
		$row = mysql_fetch_array($result[0]);
		$mapx = $row['mapx'];
		$mapy = $row['mapy'];
	}
	
	$q = 'insert into pointcal values ('.$datetime.', "'.DBFix($name).'", '.'"'.DBFix($username).'", '. $mapx.', '.$mapy.', '.$mapw;
	
	
	for ($i=0; $i < $nTotalMacs; $i++)
	{
		$q .= ',' . $vector[$i];
	}
	$q .= ',"'.DBFix($nplatform).'"';
	$q .= ')';
	
	//~ if ($bDebug) echo $q;
	//~ echo $q;
	
	return DB($q);
}


?>



