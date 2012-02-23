<?
// Dependencies: Database connection, lib_common.php, lib_macdata.php, 

function createVector($n) { $ret = array(); for ($i=0; $i<$n; $i++) $ret[$i] = 0; return $ret;}

// Return best guesses of Location given array of signals and strengths
function lookup($data, $bDebug, $platform, $minTime, $maxTime)
{
	if (!$platform) $platform = 'win';
	if ($platform=='win') $nplatform = 0;
	elseif ($platform=='linux') $nplatform = 1;
	elseif ($platform=='mac') $nplatform = 2;

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
	
	// Windows machines only look up windows data. Other platforms look at their data as well as the windows data.
	if ($nplatform == 0) $wherecondition = ' where platform=0 ';
	else $wherecondition = ' where platform=0 or platform='.$nplatform;
	
	// add time dependancy
	// example: time > "2009-01-09 15:11:31" AND time < "2009-03-24 15:20:01"
	$wherecondition .= " AND time > \"" . date("Y-m-d H:i:s", $minTime) . "\" AND time < \"" . date("Y-m-d H:i:s", $maxTime) . "\"";

	// "space distance" is not a distance in physical space. It is a distance in 76D space.
	//$q = 'select placename from point where true order by SQRT('; //Square root does not change ordering.
	// $q = 'select placename, spacedistance as (';  from point where true order by spacedistance limit 10';
	//$q = 'select distinct placename,mapx,mapy,mapw from point where true order by (';
	
	$q = 'SELECT placename, mapx, mapy, mapw, min(';
		
	for ($i=0; $i < $nTotalMacs; $i++)
	{
		$q .= 'pow(coord'.($i+1).' - '. $vector[$i].',2)+';
	}
	$q .= ' 0) AS score FROM pointcal '.$wherecondition.' GROUP BY placename ORDER BY score ASC LIMIT 10';
	
	if ($bDebug) echo $q;
	
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
