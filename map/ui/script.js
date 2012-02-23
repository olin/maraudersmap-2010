//Now with more ajax.

function xmlhttpGet(strURL, fn)
{
	var xmlHttpReq = false;
	var self = this;
	
	if (window.XMLHttpRequest) // Mozilla/Safari
		self.xmlHttpReq = new XMLHttpRequest();
	
	else if (window.ActiveXObject) // IE
		self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
		
	self.xmlHttpReq.open('GET', strURL, true);
	self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	self.xmlHttpReq.onreadystatechange = function()
	{
		if (self.xmlHttpReq.readyState == 4)
			fn(self.xmlHttpReq.responseText);
	}
	self.xmlHttpReq.send(null);
}