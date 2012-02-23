<?
// Dependencies: Database connection, lib_common.php, lib_macdata.php, 

function createVector($n) { $ret = array(); for ($i=0; $i<$n; $i++) $ret[$i] = 0; return $ret;}

// Return best guesses of Location given array of signals and strengths
function lookup($data, $bDebug=false)
{
	$nTotalMacs = countTotalMacs();
	
	// Vector with 76 + 1 components
	$vector = createVector($nTotalMacs);
	
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
		
		$vector[$nSpot] = $fStrength;
	}
	if ($bDebug) print_r($vector);
	
	
	// "space distance" is not a distance in physical space. It is a distance in 76D space.
	//$q = 'select placename from point where true order by SQRT('; //Square root does not change ordering.
	// $q = 'select placename, spacedistance as (';  from point where true order by spacedistance limit 10';
	$q = 'select distinct placename,mapx,mapy,mapw from pointcal where true order by (';
	for ($i=0; $i < $nTotalMacs; $i++)
	{
		$q .= 'pow(coord'.($i+1).' - '. $vector[$i].',2)+';
	}
	$q .= '0) limit 10';
	if ($bDebug) echo $q;
	//~ echo $q;
	
	list($ret,$n) = DB($q);
	
	$results = array();
	while ($result = mysql_fetch_array($ret))
	{
		if ($bDebug) echo $result['placename']."\n";
		
		$result['spacedistance'] = 0; //We didn't get this :(
		// It would be nice if we could return "space distance" as well.
		array_push($results, $result['placename'].'|'.$result['spacedistance'].'|'.$result['mapx'].'|'.$result['mapy'].'|'.$result['mapw']);
	}
	
	return implode(';', $results);
}


?>
