<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Marauder's Map @ Olin</title>
<link type="text/css" rel="stylesheet" href="ui.css" />
<style type="text/css">
a:link { color: #000000;}
a:visited { color: #000000;}
a:active { color: #000000;}
a:hover { color: #000000;}
body {font-family: Verdana, Arial, Helvetica; font-size:0.8em}

.boxhead {
	background:#F3F0E7;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	border:1px solid #C8BA92;
	padding:5px;
	width:150px;
}

.boxbody {
	background:#FFFFFF;
	font-family:arial;
	font-size:12px;
	border-left:1px solid #C8BA92;
	border-right:1px solid #C8BA92;
	border-bottom:1px solid #C8BA92;
	padding:5px;
	width:150px;
}

.findHeader {
	background:#F3F0E7;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	border:1px solid #C8BA92;
	padding:5px;
	width:150px;
	position: absolute;
	z-index: 1;
	display: none;
}

.findBody {
	background:#FFFFFF;
	font-family:arial;
	font-size:12px;
	border-left:1px solid #C8BA92;
	border-right:1px solid #C8BA92;
	border-bottom:1px solid #C8BA92;
	padding:5px;
	width:150px;
	position: absolute;
	z-index: 1;
	display: none;
}


</style>
<link rel="stylesheet" type="text/css" href="autosuggest.css" />
<script type="text/javascript" src="autosuggest2.js"></script>
<script type="text/javascript" src="script.js"></script>
<script src="boxover.js"></script>

<link rel="shortcut icon" href="http://acl.olin.edu/map/favicon.ico" type="image/x-icon" />
<link rel="icon" href="http://acl.olin.edu/map/favicon.ico" type="image/x-icon" />

<script type="text/javascript">
// Marauder's Map. Ben Fisher & Andy Barry

var nameArray = [];
var xArray = [];
var yArray = [];
var placeArray = [];
var timeArray = [];
var mapArray = [];
var floorArray = [];
var iconArray = [];

var numPeople = 0;

var findPersonIcon = -1;

function OnBodyLoad()
{
	var oTextbox = new AutoSuggestControl(document.getElementById("txtFind"), new NameSuggestions());
	refreshMap('auto_refresh');
	$('txtFind').focus();
}

//---------------- newFind -------------------------

/**
 * Provides suggestions for state names (USA).
 * @class
 * @scope public
 */
function NameSuggestions() {

}

/**
 * Request suggestions for the given autosuggest control. 
 * @scope protected
 * @param oAutoSuggestControl The autosuggest control to provide suggestions for.
 */
NameSuggestions.prototype.requestSuggestions = function (oAutoSuggestControl /*:AutoSuggestControl*/,
                                                          bTypeAhead /*:boolean*/) {
    var aSuggestions = [];
    var sTextboxValue = oAutoSuggestControl.textbox.value;
    
    if (sTextboxValue.length > 0)
    {
    	
    	aSuggestions = ForLoopFind(sTextboxValue)
    	
        //search for matching states
        /*
        for (var i=0; i < nameArray.length; i++) { 
            if (nameArray[i].indexOf(sTextboxValue) == 0) {
                aSuggestions.push(nameArray[i]);
            } 
        }*/
    }

    //provide suggestions to the control
    oAutoSuggestControl.autosuggest(aSuggestions, bTypeAhead);
};

function ForLoopFind(textboxValue)
{
	var myRegExp = new RegExp(textboxValue, "i");
	var outArray = [];
	
	for ( var i = 0; i < nameArray.length; i++)
	{
		if (nameArray[i].search(myRegExp) != -1)
		{
			outArray.push(nameArray[i]);
		}
	}
	return outArray;
}



//-------------------------

function $(strO) { return document.getElementById(strO);}

g_currentMap = -1;

// Keep track of how many IMG elements have been created. They are recycled.
g_imgarray = [];
g_nUsedImages = -1;

function drawIcon(x, y, name, place, time, fadedIcon, map, floorNum, iconName, boolFind)
{
	// occasionally there is a bug where the name will be blank -- don't display those
	if (name == "")
	{
		return;
	}
	
	tempArray = TransferLoc(x, y, name, place, time, floorNum, map);
	x = tempArray[0];
	y = tempArray[1];

	g_nUsedImages++;
	// Make a new IMG element if necessary.
	if (g_nUsedImages>= g_imgarray.length)
	{
		var newimg = document.createElement('img');
		newimg.src = 'p01.gif';
		newimg.style.position='absolute';
		g_imgarray.push(newimg);
		$('divMap').appendChild(newimg);
	}
//	g_imgarray[g_nUsedImages].alt = name;
	g_imgarray[g_nUsedImages].src = 'icons/' + iconName;
	if (boolFind == false)
	{
		g_imgarray[g_nUsedImages].title = 'header=[' + name + '] body=[' + place + '<br>' + time + '] cssbody=[boxbody] cssheader=[boxhead]';
	} else
	{
		// show the find box
		var findHeader = $('findHeader');
		var findBody = $('findBody');
		
		findPersonIcon = g_nUsedImages;
		
		findHeader.innerHTML = name;
		findBody.innerHTML = place + '<br>' + time;
		
		findHeader.style.left = (x + 15) + 'px';
		findHeader.style.top = (y + 20) + 'px';
		
		findHeader.style.display = 'inline';
		
		findBody.style.left = (x + 15) + 'px';
		findBody.style.top = (y + findHeader.offsetHeight + 20) + 'px';

		findBody.style.display = 'inline';
	}
	
	g_imgarray[g_nUsedImages].style.left = (x-5) + 'px'; //Correction because the center != the top left
	g_imgarray[g_nUsedImages].style.top = (y-10) + 'px';
	g_imgarray[g_nUsedImages].style.display = '';
	
	if (fadedIcon == true)
	{
		g_imgarray[g_nUsedImages].style.opacity = '0.4';
	} else
	{
		g_imgarray[g_nUsedImages].style.opacity = '1';
	}
	return g_nUsedImages;
}

function TransferLoc(x, y, name, placeString, time, floorNum, map)
{
	// we use the placeString to figure out what building we are in
	// -- we need to use the name string to in case of multiple icons
	place = name + ' ' + placeString;

	// map 1 = AC
	// map 2 = WH/EH
	x = parseInt(x);
	y = parseInt(y);
	floorNum = parseInt(floorNum);

	if (map == 1)
	{
		x = Math.round(x * 0.85);
		// 440 is the post-scaled value, so we need to scale beforehand
		x = x + 440;

	}

	if (map == 2)
	{
		x = x - 331;
		x = Math.round(x * 0.85);
		
		// 331 is the prescaled value, so scale afterwords
	}

	// both maps are scaled by 85%
	y = Math.round(y * 0.85);


	// new map -- replace markers by somemore
	x = x + 33;
	y = y + 77;
	// figure out which building we are in
	if (place.indexOf("WH") >= 0)
	{
		//x = x + 33;
		//y = y + 77;
	} else if (place.indexOf("EH") >= 0)
	{
		y = y + 50;
	} else if (place.indexOf("CC") >= 0)
	{
		x = x + 100;
		y = y + 100;
	} else if (place.indexOf("MH") >= 0)
	{
		x = x + 100;
	} else if (place.indexOf("AC") >= 0)
	{
		x = x + 100;
	}
	
	// ok scale some more
	x = Math.round(x * 0.7692);
	y = Math.round(y * 0.7692);
	
	// ------------- floor movement ---------------- //
	// convert floorNum to the number we need to multiply by (1 = 3, 2 = 2, 3 = 1, 4 = 0)
	floorNum = 4 - floorNum;
	
	//alert(name + ' x: ' + x + ' y: ' + y);
	moveX = 0;
	moveY = 0;
	
	// --------------- West Hall --------------- //
	if (x < 180 && y < 125)
	{
		// WH west wing
		//move to the left and up	
		moveX = -10 * floorNum;
	} else if (x > 187 && x < 225 && y < 150)
	{
		// WH west wing right
		moveX = 10 * floorNum;
		
	} else if (x > 228 && x < 400 && y < 155)
	{
		// WH north wing, top
		moveY = -14 * floorNum;

	} else if (x > 205 && x < 400 && y > 165 && y < 230)
	{
		// WH north wing, bottom
		
		moveY = 17 * floorNum;
	} else if (x < 160 && y > 165 && y < 300)
	{
		// WH east wing, left
		moveX = -15 * floorNum;
	} else if (x > 160 && x < 200 && y > 174 && y < 290)
	{
		// WH east wing, right
		moveX = 15 * floorNum;
		moveY = 7 * floorNum;
	} else if (x < 100 && y > 500)
	{
		// --------------- East Hall --------------- //

		// EH east wing, left
		moveX = -15 * floorNum;

	} else if (x > 100 && x < 150 && y > 500)
	{
		// EH east wing, right
		moveX = 18 * floorNum;
	} else if (x < 200 && y < 455 && y > 307)
	{
		// EH west wing, left
		moveX = -16 * floorNum;
	} else if (x > 133 && x < 175 && y < 457 && y > 315)
	{
		// EH west wing, right
		moveX = 20 * floorNum;
	} else if (x > 166 && x < 380 && y < 480 && y > 430)
	{
		// EH north wing, top
		moveY = -13 * floorNum;
	} else if (x > 165 && x < 380 && y > 480)
	{
		// EH north wing, bottom
		moveY = 15 * floorNum;
		
	} else if (x > 145 && x < 165 && y > 479 && y < 515)
	{
		// EH 120
		moveY = 24 * floorNum;
	} else if (place.indexOf("AC") >= 0 && place.indexOf("Inside") >= 0)
	{
		// --------------- AC --------------- //
		moveX = 15 * floorNum;
		moveY = -17 * floorNum - 6;
		//don't move at all if on the 4th floor
		if (floorNum == 0)
		{
			moveY = 0;
		}
		/*
		moveX = -15 * floorNum;
		moveY = -30 * floorNum - Math.round(0.3 * y) + 35;
		*/
	} else if (x > 410 && x < 715 && y < 350 && y > 250)
	{
		// --------------- MH --------------- //
		// MH top
		moveY = -15 * floorNum;
		
	} else if (x > 650 && x < 715 && y > 340 && y < 430)
	{
		// MH right
		moveX = 20 * floorNum;
		
	} else if (x > 500 && x < 650 && y > 400 && y < 550)
	{
		// MH bottom
		moveY = 15 * floorNum;
		
	} else if (x > 420 && x < 515 && y > 350 && y < 450)
	{
		// MH left
		moveX = -20 * floorNum;
	}// else if ()
	//{
		// --------------- CC --------------- //
		
		// turns out that people don't really go onto the floors
		// in the CC very much
	//}
	
	
	x = x + moveX;
	y = y + moveY;
	
	// Victoria's map is +28 to x
	x = x + 28;
	
	return [x, y];
}

function modifyIcon(id, x, y, name, place, time, fadedIcon, map, floorNum, iconName)
{
	tempArray = TransferLoc(x, y, name, place, time, floorNum, map);
	x = tempArray[0];
	y = tempArray[1];

	id = parseInt(id);
	g_imgarray[id].src = 'icons/' + iconName;
	g_imgarray[id].title = 'header=[' + name + '] body=[' + place + '<br>' + time + '] cssbody=[boxbody] cssheader=[boxhead]';
	g_imgarray[id].style.left = (x-5) + 'px'; //Correction because the center != the top left
	g_imgarray[id].style.top = (y-10) + 'px';
	g_imgarray[id].style.display = '';
	
	if (fadedIcon == true)
	{
		g_imgarray[id].style.opacity = '0.5';
	} else
	{
		g_imgarray[id].style.opacity = '1';
	}
}



function hidePersonIcons()
{
	for (var i=0; i<g_imgarray.length; i++)
		g_imgarray[i].style.display='none';
}

function refreshMap(strAutoRefresh)
{   
	hidePersonIcons();
	$('spanstatus').innerHTML = 'Loading...';
	
	$('img01').style.display = '';
	
	$('btnRefresh').style.display = '';
	getData('1');
	
	if (strAutoRefresh=='auto_refresh')
		setTimeout('refreshMap("auto_refresh")', 2*1000*60); //refresh again in 2 minutes
}

function getData(mapw)
{
	var strUrl = 'map_backend.php?mapw='+mapw;
	if (mapw == '1')
	{
		xmlhttpGet(strUrl, loadCallback1); // Asynchronous call
	} else
	{
		xmlhttpGet(strUrl, loadCallback2); // Asynchronous call
	}
}

function loadCallback1(retData)
{
	numPeople = 0;
	loadCallback(retData, 1);
	getData('2')
}

function loadCallback2(retData)
{
	loadCallback(retData, 2);
}

function loadCallback(retData, map)
{
	if (map == 1)
	{
		//refresh data
		nameArray = [];
		xArray = [];
		yArray = [];
		placeArray = [];
		timeArray = [];
		mapArray = [];
		floorArray = [];
		iconArray = [];
	}
	
	retData = retData.replace(/\r\n/g,'\n').replace(/\n/g,''); //Remove newlines.
	retData = retData.replace(/\"/, '');
	if (retData.indexOf('success:')!=0)
	{
		//alert('An error occurred.');could be standby, etc.
		if ($('chkDebug').checked) $('spanstatus').innerHTML = 'Error: '+retData;
		return;
	}
	retData = retData.substring('success:'.length); // Remove the string 'success:' from the data.
	
	// Display data if debugging is checked.
	if ($('chkDebug').checked) alert(retData);
	
	var objUsedPixelLocations = new Object;
	var year;
	var day;
	var month;
	var hour;
	var minute;
	var am;
	
	var icon;
	
	var titleString;
	var bodyString;
	
	var myDate = new Date();
	var now = new Date();
	
	if (retData)
	{

		$('spanstatus').innerHTML = '';
		// Parse results from the format 501|163|Andrew Barry|Inside WH309|time|floor|icon.gif;
		
		aPeople = retData.split(';');
		
		for (var i=0; i<aPeople.length;i++)
		{	
		
			aPeopleData = aPeople[i].split('|');
			
			// Format the date and time
			year = aPeopleData[4].substring(0, 4);
			month = aPeopleData[4].substring(5, 7);
			day = aPeopleData[4].substring(8, 10);
			
			if (month.substring(0, 1) == '0')
			{
				month = month.substring(1, 2);
			}
			
			if (day.substring(0, 1) == '0')
			{
				day = day.substring(1, 2);
			}
			
			
			hour = parseInt(aPeopleData[4].substring(11,13), 10);
			minute = aPeopleData[4].substring(14, 16);
			
			myDate.setUTCFullYear(year);
			myDate.setMonth(month - 1);
			myDate.setDate(day);
			
			myDate.setHours(hour);
			myDate.setMinutes(minute);

			if (hour < 12)
			{
				am = "AM";
			} else
			{
				am = "PM";
			}
			
			if (hour == 0)
			{
				hour = 12;
			}
			
			if (hour > 12)
			{
				hour = hour - 12;
			}
		
			//add the entry to the find array
			
			//escape the name
			aPeopleData[2] = aPeopleData[2].replace(/[<>]/g, "");
			
			xArray.push(aPeopleData[0]);
			yArray.push(aPeopleData[1]);
			nameArray.push(aPeopleData[2]);
			placeArray.push(aPeopleData[3]);
			timeArray.push(month + '/' + day + ' ' + hour + ':' + minute + ' ' + am);
			mapArray.push(map);
			floorArray.push(aPeopleData[5]);
			iconArray.push(aPeopleData[6]);

			// check to see if it is current enough to go on the map
			fadedIcon = false;
			
			if (myDate.getTime() > now.getTime() - 6000000) //100 minutes (in miliseconds)
			//if (myDate.getTime() > now.getTime() - 600000000) //100 minutes (in miliseconds)
			{
				icon = aPeopleData[6];
				// check to see if it should be faded 1200000 = 20 min
				if (myDate.getTime() < now.getTime() - 1200000)
				{
					fadedIcon = true;
					//icon = 'p_fade.gif';
				}
				
				timeAgo = now - myDate;
				if (timeAgo > 3600000)
				{
					// since we only diplay up to 100 minutes, we just say 'more than an hour ago'
					timeAgoString = '1 hour ago';
					
					
				} else if (timeAgo < 90000)
				{
					timeAgoString = '1 minute ago';
				} else
				{
					
					timeAgoString = Math.round(timeAgo/60000) + ' minutes ago';
				}
				
				numPeople = numPeople + 1;
				//aPeopleData[4] = month + '/' + day + ' ' + hour + ':' + minute + ' ' + am;
				aPeopleData[4] = hour + ':' + minute + ' ' + am;
				
				// Check if items are on top of each other. Assumes that z-index of the last created will be topmost
				var strKey = aPeopleData[0].toString()+','+aPeopleData[1].toString();
				
				// drawIcon(x, y, name, place, time, fadedIcon, map, floorNum, iconName, boolFind)
				
				if (objUsedPixelLocations[strKey])
				{
					
					
					// the location is already in use, so get rid of the old icon at this space and place
					// a multiple-person icon.
					
					// get the data for the other people
					oldPeople = objUsedPixelLocations[strKey].split('|');
					
					//this includes the extra person (added below) because it has the ID field
					titleString = aPeopleData[3] + '<br>(' + oldPeople.length + ' people)';
					
					bodyString = '';
					
					for (var j=1;j<oldPeople.length;j++)
					{
						// for each person in this group...
						thisPerson = oldPeople[j].split(';');
						
						if (j > 1)
						{
							bodyString += '<br><br>';
						}
						
						bodyString += '<img src="icons/' + thisPerson[4] + '" style="float: left; margin-right: 5px; margin-bottom: 10px;';
						
						if (thisPerson[3] == 'true')
						{
							bodyString += ' opacity: 0.5;';
						}
						
						bodyString += '" />';
						
						if (thisPerson[3] == 'true')
						{
							bodyString += '<font color="#808080"><b>' + thisPerson[0] + '</b><br>' + thisPerson[2] + '</font>';
						} else
						{
							bodyString += '<b>' + thisPerson[0] + '</b><br>' + thisPerson[2];
						}
						
					}
					
					// finally, add in this user
					bodyString += '<br><br>';
					
					bodyString += '<img src="icons/' + aPeopleData[6] + '" style="float: left; margin-right: 5px; margin-bottom: 100%;';
					
					if (fadedIcon == true)
					{
						bodyString += ' opacity: 0.5;';
					}
					
					bodyString += '" />';
					
					if (fadedIcon == true)
					{
						bodyString += '<font color="#808080">';
					}
					
					bodyString += '<b>' + aPeopleData[2] + '</b><br>' + timeAgoString;
					
					if (fadedIcon == true)
					{
						bodyString += '</font>';
					}
					
					//(id, x, y, name, place, time, fadedIcon, map, floorNum, iconName)
					iconString = 'p' + oldPeople.length + '.gif';
					if (oldPeople.length > 5)
					{
						iconString = 'p5.gif';
					}
					
					modifyIcon(oldPeople[0], aPeopleData[0], aPeopleData[1], titleString, bodyString, '', false, map, aPeopleData[5], iconString);
					
					objUsedPixelLocations[strKey] += '|' + aPeopleData[2] + ';' + aPeopleData[3] + ';' + timeAgoString + ';' + fadedIcon + ';' + aPeopleData[6];
					
					
				} else
				{
					//x, y, name, place, time, map, floorNum, iconName, boolFind
					titleString = aPeopleData[2];
					placeString = aPeopleData[3];
					
					if (fadedIcon == true)
					{
						titleString = '<font color="#808080">' + titleString + '</font>';
						timeAgoString = '<font color="#808080">' + timeAgoString + '</font>';
						placeString = '<font color="#808080">' + placeString + '</font>';
						
					}
					
					if (fadedIcon == true)
					{
						placeString = '<img src="icons/' + aPeopleData[6] + '" style="float: left; margin-right: 3px; margin-bottom: 100%; opacity: 0.5;" />' + placeString;
						
					} else
					{
						placeString = '<img src="icons/' + aPeopleData[6] + '" style="float: left; margin-right: 3px; margin-bottom: 100%;" />' + placeString;
					}
					
					var iconId = drawIcon( aPeopleData[0], aPeopleData[1], titleString, placeString, timeAgoString, fadedIcon, map, aPeopleData[5], icon, false);
					
					objUsedPixelLocations[strKey] = iconId + '|' + aPeopleData[2] + ';' + aPeopleData[3] + ';' + timeAgoString + ';' + fadedIcon + ';' + aPeopleData[6];

					//objUsedPixelLocations[strKey] = aPeopleData[2] + ': ' +aPeopleData[3] + '\n';
					
				}
			}
		}
		$('spanstatus').innerHTML = numPeople + ((numPeople==1) ? ' person' : ' people');
	}
	else
	{
		$('spanstatus').innerHTML = '0 people';
	}
}

function Find()
{

	// clear old values
	ClearFind();
	
	// check to see if there is only one option being displayed
	// if so, find that person
	var textbox = $('txtFind');
	var searchName = textbox.value;
	
	var findLoopArray = [];
	findLoopArray = ForLoopFind(textbox.value);
	
	if (findLoopArray.length == 1)
	{
		searchName = findLoopArray[0];
	}

	var textbox = $('txtFind');
	var id = -1;
	
	//get the ID for the value that is in the textbox
	for ( var i = 0; i < nameArray.length; i++)
	{
		if (nameArray[i] == searchName)
		{
			id = i;
		}
	}
	
	if (id == -1)
	{
		return false;
	}

	//x, y, name, place, time, fadedIcon, map, floorNum, iconName, boolFind
	drawIcon(xArray[id], yArray[id], nameArray[id], '<img src="icons/' + iconArray[id] + '" style="float: left; margin-right: 3px; margin-bottom: 10px;" />' + placeArray[id], timeArray[id], false, mapArray[id], floorArray[id], iconArray[id], true);
	
	//return false so when this is called from a form the form is not submitted
	return false;
}

function ClearFind()
{
	if (findPersonIcon != -1)
	{
		var findHeader = $('findHeader');
		var findBody = $('findBody');
	
		findHeader.style.display = 'none';
		findBody.style.display = 'none';
		g_imgarray[findPersonIcon].style.display='none';
	
		findPersonIcon = -1;
	}
}

</script>


</head>
<body onLoad="OnBodyLoad();" onClick="ClearFind();">

<div id="divPanel">

<span style="float: left;">
	<button onclick="refreshMap('no_auto_refresh');" style="display:none" id="btnRefresh">Refresh</button> <span id="spanstatus"> </span>
	<form onSubmit="return Find();" autocomplete="off">
		<input type="text" id="txtFind"  autocomplete="off" style="position:absolute; left: 200px; top:5px; width:150px;" />
		<button id="btnFind" style="position:absolute; left: 360px; top:3px; width:4em;">Find</button>
		<span style="display:none"><input type="checkbox" id="chkDebug" style="margin-left:400px" /> Debug</span>
		
	</form>
</span>

<span id="spanabout" style="float: right;">
	<a href="../index.php" style="color: white;">The Marauder's Map</a>
</span>
</div>
	


<div id="divMap" style="position:absolute; left: 5px; top:35px;"><!--  style="position:absolute; left:5px; top:75px" -->
<img id="img01" src="map2.jpg" style="display:none;" />
</div>

	<!--
	<select id="listFind" size="12" width="100" style="position:absolute; left: 200px; top:30px; width:200px; display:none;">
	</select>
	-->
	
	<div class="findHeader" id="findHeader" onClick="ClearFind();">
		
	</div>
	<div class="findBody" id="findBody" onClick="ClearFind();">
		
	</div>

</body>
</html>
