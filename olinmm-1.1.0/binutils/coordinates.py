import os
import sys

def getavgcoords(n=3, tsleep = 0.15):
    import time
    totals = {}
    for i in range(n):
        print i
        res = getcoordinates()
        for spot in res:
            if spot in totals:
                totals[spot][0] += res[spot][0]
            else:
                totals[spot] = res[spot]
        time.sleep(tsleep)
        
    # And divide each by n
    for spot in totals:
        totals[spot][0] = totals[spot][0] / (float(n))

    return totals
    

def getcoordinates():
    binutilspath = getpath()
    coord = Coordinate()
    ret = {}
    if sys.platform=='win32':
        o = os.popen(os.path.join(binutilspath, 'getcoords.exe'))
        res = o.read().replace('\r\n','\n').split('\n')
        o.close()
        
        for line in res:
            if line=='': continue
            linepts = line.split(',')
            if 'OLIN' in linepts[2] and 'GUEST' not in linepts[2]: #don't allow other wifi hotspots!
                coord.strength = interpretDB(linepts[0])
                coord.mac = linepts[1]
                coord.name = linepts[2]
                
                ret[coord.mac] = [coord.strength, coord.name]
    elif sys.platform=='linux2':
        o = os.popen(binutilspath + 'mmscan')
        res = o.read().replace('\r\n','\n').split('\n')
        o.close()
        # now we have the data -- it looks like
        # ['00:20:D8:2D:63:C2', '"OLIN_GUEST"', 'Signal level=-90 dBm', '00:15:E8:E3:75:80', '"OLIN_CC"', 'Signal level=-57 dBm',
        #'62:32:4C:CE:3A:1B', '"hpsetup"', 'Signal level=-79 dBm', '00:20:D8:2D:2C:C2', '"OLIN_GUEST"', 'Signal level=-82 dBm',
        #'00:20:D8:2D:2C:C0', '"OLIN_CC"', 'Signal level=-82 dBm', '00:20:D8:2D:63:C0', '"OLIN_CC"', 'Signal level=-91 dBm', '']
        
        # parse it by threes
        count = 0
        for thisResult in res:
            if (count == 0):
                coord.mac = thisResult
            elif (count == 1):
                coord.name = thisResult
                coord.name = coord.name.replace('"', '')
            elif (count == 2):
                coord.strength = thisResult.replace('Signal level=', '')
                coord.strength = coord.strength.replace(' dBm', '')
                coord.strength = interpretDB(coord.strength)
                # negative one so when we add one to it, it becomes zero
                count = -1
                #make sure that this is an Olin point
                if 'OLIN' in coord.name and 'GUEST' not in coord.name:
                    ret[coord.mac] = [coord.strength, coord.name]
            count = count + 1
    elif sys.platform=='darwin':
        import plistlib
        cmd = '/System/Library/PrivateFrameworks/Apple80211.framework/Resources/airport -s -x'
        ntwks = []
        try:
            ntwks = plistlib.readPlist(os.popen(cmd))
        except:
            print "Failed to find networks.  The command '%s' may not exist." % cmd
        
        for network in ntwks:
            if 'OLIN' in network['SSID_STR'] and 'GUEST' not in network['SSID_STR']:
                macbytes = network['BSSID'].split(':')
                bssid = []
                for byte in macbytes:
                    if len(byte) < 2:
                        bssid.append(('0%s' % byte).upper())
                    else:
                        bssid.append(byte.upper())
                ret[':'.join(bssid)] = [interpretDB(network['RSSI']), network['SSID_STR']]
    return ret
    
class Coordinate:
    name = ''
    strength = ''
    mac = ''

def interpretDB(string):
    return 100 + int(string)
    
def getpath():
    if sys.platform=='linux2' and os.path.exists('/usr/bin/olinmm.py') and os.path.exists('/usr/share/olinmm'):
        return '/usr/share/olinmm/binutils/'
    elif sys.platform=='linux2':
        return 'binutils/'
    else:
        return '.\\binutils\\'
        #~ pathname = os.path.split( os.path.abspath(sys.argv[0]))[0]
        #~ return os.path.join(pathname, 'binutils')

if __name__ == '__main__':
    # test code
    print getavgcoords(10, 0.15)


