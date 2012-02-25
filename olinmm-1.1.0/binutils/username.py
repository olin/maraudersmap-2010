import os
import sys

def getusername():
	binutilspath = getpath()
	if sys.platform=='win32':
		o = os.popen(os.path.join(binutilspath, 'getusername.exe'))
		res = o.read().replace('\r\n','\n').strip()
		o.close()
		return res
		
	elif sys.platform=='linux2' or sys.platform=='darwin':
		o = os.popen('whoami')
		res = o.read().replace('\r\n','\n').strip()
		o.close()
		return res
	else:
		return "Unknown User"
		
def getpath():
	return '.\\binutils\\'
	#~ pathname = os.path.split( os.path.abspath(sys.argv[0]))[0]
	#~ return os.path.join(pathname, 'binutils')

