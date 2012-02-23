<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Marauder's Map @ Olin</title>

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

<script type="text/javascript">
// Maurader's Map. Ben Fisher & Andy Barry

var nameArray = [];
var xArray = [];
var yArray = [];
var placeArray = [];
var timeArray = [];
var mapArray = [];
var floorArray = [];

var numPeople = 0;

var findPersonIcon = -1;

function OnBodyLoad()
{
	var oTextbox = new AutoSuggestControl(document.getElementById("txtFind"), new NameSuggestions());
	refreshMap('auto_refresh');
}

//--------------- newFind -------------------------

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
    
    if (sTextboxValue.length > 0){
    
    	var myRegExp = new RegExp(sTextboxValue, "i");

		for ( var i = 0; i < nameArray.length; i++)
		{
			if (nameArray[i].search(myRegExp) != -1)
			{
				aSuggestions.push(nameArray[i]);
			}
		}
    
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

//-------------------------

function $(strO) { return document.getElementById(strO);}

g_currentMap = -1;

// Keep track of how many IMG elements have been created. They are recycled.
g_imgarray = [];
g_nUsedImages = -1;

function drawIcon(x, y, name, place, time, map, iconName, boolFind)
{
	// occasionally there is a bug where the name will be blank -- don't display those
	if (name == "")
	{
		return;
	}
	// map 1 = AC
	// map 2 = WH/EH
	x = parseInt(x);
	y = parseInt(y);

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
		
		findHeader.style.left = (x + 25) + 'px';
		findHeader.style.top = (y + 20) + 'px';
		
		findHeader.style.display = 'inline';
		
		findBody.style.left = (x + 25) + 'px';
		findBody.style.top = (y + findHeader.offsetHeight + 20) + 'px';

		findBody.style.display = 'inline';
	}
	
	g_imgarray[g_nUsedImages].style.left = (x-5) + 'px'; //Correction because the center != the top left
	g_imgarray[g_nUsedImages].style.top = (y-10) + 'px';
	g_imgarray[g_nUsedImages].style.display = '';
	return g_nUsedImages;
}

function modifyIcon(id, x, y, name, place, time, map, iconName)
{
	// map 1 = AC
	// map 2 = WH/EH
	x = parseInt(x);
	y = parseInt(y);
	
	if (map == 1)
	{
		x = x + 440;

	}

	if (map == 2)
	{
		x = x - 331;
	}

	// both maps are scaled by 85%

	x = Math.round(x * 0.85);
	y = Math.round(y * 0.85);

	id = parseInt(id);
	g_imgarray[id].src = 'icons/' + iconName;
	g_imgarray[id].title = 'header=[' + name + '] body=[' + place + '<br>' + time + '] cssbody=[boxbody] cssheader=[boxhead]';
	g_imgarray[id].style.left = (x-5) + 'px'; //Correction because the center != the top left
	g_imgarray[id].style.top = (y-10) + 'px';
	g_imgarray[id].style.display = '';
}



function hidePersonIcons()
{
	for (var i=0; i<g_imgarray.length; i++)
		g_imgarray[i].style.display='none';
}

function refreshMap(strAutoRefresh)
{   
	/*
	if (nMap==0) nMap = g_currentMap; // Default to the previously shown map
	hidePersonIcons();
	$('spanstatus').innerHTML = 'Loading...';
	if (nMap==1) //Academic center
	{
		$('img01').style.display = '';
		$('img02').style.display = 'none';
		getData('1');
		g_currentMap = 1;
	}
	else if (nMap==2) //WH, EH
	{
		$('img01').style.display = 'none';
		$('img02').style.display = '';
		getData('2');
		g_currentMap = 2;
	}
	*/
	
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
		// Parse results from the format 23|25|bfisher|time;46|67|abarry|time
		
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
			
			
			hour = parseInt(aPeopleData[4].substring(11,13));
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
			
			xArray.push(aPeopleData[0]);
			yArray.push(aPeopleData[1]);
			nameArray.push(aPeopleData[2]);
			placeArray.push(aPeopleData[3]);
			timeArray.push(month + '/' + day + ' ' + hour + ':' + minute + ' ' + am);
			mapArray.push(map);
			floorArray.push(aPeopleData[5]);

			// check to see if it is current enough to go on the map
			
			if (myDate.getTime() > now.getTime() - 6000000) //100 minutes (in miliseconds)
			{
				icon = 'p0' + aPeopleData[5];
				// check to see if it should be faded 1200000 = 20 min
				if (myDate.getTime() < now.getTime() - 1200000)
				{
					icon = icon + '_fade';
				}
				icon = icon + '.gif';

				numPeople = numPeople + 1;
				//aPeopleData[4] = month + '/' + day + ' ' + hour + ':' + minute + ' ' + am;
				aPeopleData[4] = hour + ':' + minute + ' ' + am;
				
				// Check if items are on top of each other. Assumes that z-index of the last created will be topmost
				var strKey = aPeopleData[0].toString()+','+aPeopleData[1].toString();
				
				// drawIcon(x, y, name, place, time, map, iconName, boolFind)
				
				if (objUsedPixelLocations[strKey])
				{
					
					
					// the location is already in use, so get rid of the old icon at this space and place
					// a multiple-person icon.
					
					// get the data for the other people
					oldPeople = objUsedPixelLocations[strKey].split('|');
					
					//this includes the extra person (added below) because it has the ID field
					titleString = oldPeople.length + ' people';
					
					bodyString = '';
					
					for (var j=1;j<oldPeople.length;j++)
					{
						// for each person in this group...
						thisPerson = oldPeople[j].split(';');
						
						if (j > 1)
						{
							bodyString += '<br><br>';
						}
						
						bodyString += '<b>' + thisPerson[0] + '</b><br>' + thisPerson[1] + '<br>' + thisPerson[2];
						
					}
					
					// finally, add in this user
					bodyString += '<br><br><b>' + aPeopleData[2] + '</b><br>' + aPeopleData[3] + '<br>' + aPeopleData[4];
					
					modifyIcon(oldPeople[0], aPeopleData[0], aPeopleData[1], titleString, bodyString, '', map, 'many.gif');
					
					objUsedPixelLocations[strKey] += '|' + aPeopleData[2] + ';' + aPeopleData[3] + ';' + aPeopleData[4];
					
					
				} else
				{
					var iconId = drawIcon( aPeopleData[0], aPeopleData[1], aPeopleData[2], aPeopleData[3], aPeopleData[4], map, icon, false);
					objUsedPixelLocations[strKey] = iconId + '|' + aPeopleData[2] + ';' + aPeopleData[3] + ';' + aPeopleData[4];

					//objUsedPixelLocations[strKey] = aPeopleData[2] + ': ' +aPeopleData[3] + '\n';
					
				}
				
				//var s = 'alert("' + objUsedPixelLocations[strKey].replace('\n','') + '");';
				//icon.onmouseup = new Function(s);
				
			}
			
		}
		$('spanstatus').innerHTML = numPeople + ((numPeople==1) ? ' person' : ' people');
	}
	else
	{
		$('spanstatus').innerHTML = '0 people';
	}
}

/*
function FindSuggest(evt)
{
	//check to see if this was an "enter" key
	if (evt.keyCode == '13')
	{
		//submit
		Find();
		return;
	}

	// first read the value out of the text box
	var textbox = $('txtFind');
	var listbox = $('listFind');
	
	if (textbox.value == '')
	{
		listbox.style.display = 'none';
	} else
	{
		listbox.style.display = '';
	}
	FindSuggestHelper( textbox.value );
}

function FindSuggestHelper( searchString )
{
	var listbox = $('listFind');

	while (listbox.hasChildNodes())
	{
	  listbox.removeChild(listbox.firstChild);
	}
	
	var myRegExp = new RegExp(searchString, "i");
	
	var number = 0;

	for ( var i = 0; i < nameArray.length; i++)
	{
		if (searchString == "" || nameArray[i].search(myRegExp) != -1)
		{
			var tempOption = new Option();
			tempOption.text = nameArray[i];
			tempOption.id = i;
			listbox.appendChild(tempOption);
			number ++;
		}
	}
	if (number == 0)
	{
		var tempOption = new Option();
		tempOption.text = '-- No Suggestions --';
		tempOption.id = 'nousers';
		listbox.appendChild(tempOption);
	} else if (number == 1)
	{
		listbox.options[0].selected = true;
	}
	
}
*/
function Find()
{

	// clear old values
	ClearFind();
	

	var textbox = $('txtFind');
	var id = -1;
	var icon = 'p0';
	
	//get the ID for the value that is in the textbox
	for ( var i = 0; i < nameArray.length; i++)
	{
		if (nameArray[i] == textbox.value)
		{
			id = i;
		}
	}
	
	if (id == -1)
	{
		return;
	}
	
	icon = icon + floorArray[id] + '_dark.gif';

	//x,y, name, place, time, map, floor, bool is find
	drawIcon(xArray[id], yArray[id], nameArray[id], placeArray[id], timeArray[id], mapArray[id], icon, true);	
	
	
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
<body onLoad="OnBodyLoad();">

<div id="divPanel">

<span style="float: left;">
	<button onclick="refreshMap('no_auto_refresh');" style="display:none" id="btnRefresh">Refresh</button> <span id="spanstatus"> </span>
	<form onSubmit="return Find();" autocomplete="off">
		<input type="text" id="txtFind"  autocomplete="off" onKeyDown="alert('hi');" style="position:absolute; left: 200px; top:5px; width:150px;" />
		<button onclick="Find();" id="btnFind" style="position:absolute; left: 360px; top:3px;">Find</button>
	</form>
	<a href="mapui.php" style="position:absolute; left: 750px; top:5px;">Try our new interface!</a>
	<input type="checkbox" id="chkDebug" style="margin-left:400px" /> Debug
</span>

<span id="spanabout" style="float: right;">
	The Marauder's Map
</span>
</div>
	


<div id="divMap" style="position:absolute; left: 5px; top:35px;"><!--  style="position:absolute; left:5px; top:75px" -->
<img id="img01" src="map.png" style="display:none;" />
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
