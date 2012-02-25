
# Ties together Python with PHP
import urllib
import sys
import os

if sys.platform=='linux2' and os.path.exists('/usr/bin/olinmm.py') and os.path.exists('/usr/share/olinmm'):
	f=open('/usr/share/olinmm/host.txt', 'r')
else:
	f=open('host.txt','r')
g_strHost = f.read()
f.close()

def sendToServer(strPhpscript, dictParams):
	strUrl = g_strHost + '/' + strPhpscript + '?'
	for param in dictParams:
		strUrl += param + '=' + str(dictParams[param]).replace(' ','%20') + '&'
	strUrl = strUrl.rstrip('&')
	print strUrl
	
	try:
		u = urllib.urlopen(strUrl)
		ret = u.read().strip()
		u.close()
	except:
		return False, 'Could not connect to server!'
	
	#See if successful
	if not ret.startswith('success:'):
		return False, ret
	else:
		return True, ret[len('success:'):]

def serializeMACData(a):
	s = ''
	for mac in a:
		s += mac + ',' + str(a[mac][0]) + ';'
	return s.rstrip(';')

def unserializePersonData(s):
	o = {}
	if s=='nobody': return 'nobody'
	s = s.split('|')
	o['username'] = s[0]
	o['placename'] = s[1]
	o['status'] = s[2]
	o['lastupdate'] = s[3]
	return o

def unserializeMACData(s):
	ret = []
	for point in s.split(';'):
		if len(point)==0: continue
		pointpts = point.split('|')
		# placename , distance , mapx, mapy, mapw
		ret.append([ pointpts[0], float(pointpts[1]), int(pointpts[2]), int(pointpts[3]), int(pointpts[4])  ])
	#name and distance pairs
	return ret

if __name__=='__main__':
	ret = sendToServer('update.php',{})
	print ret
