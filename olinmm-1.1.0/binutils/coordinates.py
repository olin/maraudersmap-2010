import os
import sys

def getavgcoords(n=3, tsleep = 0.15):
    import time
    totals = dict()
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

    ret = dict()
    # Dictionary with key : value pairs of the form
    # MAC_Address : [Signal_Strength, Network_Name]
    # Example:
    # {
    #   '00:20:D8:2D:2C:C1': [14, 'OLIN_CC'],
    #   '00:20:D8:2D:B3:C0': [12, 'OLIN_CC'],
    #   '00:20:D8:2D:65:02': [12, 'OLIN_WH'],
    #   '00:20:D8:2D:85:40': [38, 'OLIN_CC']
    # }

    # WINDOWS #FIXME: untested since the MM went offline #XXX: Won't work any longer
    if sys.platform.startswith('win'):
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
    # LINUX #FIXME: untested since the MM went offline
    elif sys.platform.startswith('linux'):
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
    # MAC OS X
    elif sys.platform.startswith('darwin'):
        import plistlib
        cmd = '/System/Library/PrivateFrameworks/Apple80211.framework/Resources/airport -s -x'
        # This is an undocumented system utility available on Mac OS X
        # From the included help:
        # -s[<arg>] --scan=[<arg>]       Perform a wireless broadcast scan.
        #		   Will perform a directed scan if the optional <arg> is provided
        # -x        --xml                Print info as XML
        ntwks = list()
        try:
            # Get information about networks 
            ntwks = plistlib.readPlist(os.popen(cmd))
        except Exception as e:
            print "Failed to find networks.  The command '%s' may not exist." % cmd
            print "Traceback is:\n%s" % e            
        
        for network in ntwks:
            if 'OLIN' in network['SSID_STR'] and 'GUEST' not in network['SSID_STR']:
                # Now we are pretty sure that this is a non-guest Olin network
                # Unless someone else has a router with 'OLIN' in the SSID
                
                # The BSSID (MAC address) is of the form 0:20:d8:2d:65:2
                # Now we need to convert it to the format 00:20:D8:2D:65:20
                macbytes = network['BSSID'].split(':')
                bssid = list()
                print 'RSSI:', network['RSSI']
                for byte in macbytes:
                    if len(byte) < 2:
                        bssid.append(('0%s' % byte).upper())
                    else:
                        bssid.append(byte.upper())
                ret[':'.join(bssid)] = [interpretDB(network['RSSI']), network['SSID_STR']]
    print "ret",ret
    return ret
    
class Coordinate:
    # XXX: The way this class is used is really silly. It should probably die. - Julian
    name = ''
    strength = ''
    mac = ''

def interpretDB(string):
    # All platforms return the Received Signal Strength Indication (RSSI) in dBm units (http://en.wikipedia.org/wiki/DBm)
    # The following is a convenient way to indicate, for example, that -85 is weaker than -10
    return 100 + int(string)
    
def getpath():
    # XXX: Only works on Windows and Linux. This should be made private unless it is used in another file - Julian
    if sys.platform.startswith('linux') and os.path.exists('/usr/bin/olinmm.py') and os.path.exists('/usr/share/olinmm'):
        return '/usr/share/olinmm/binutils/'
    elif sys.platform.startswith('linux'):
        return 'binutils/'
    else:
        return '.\\binutils\\'
        #~ pathname = os.path.split( os.path.abspath(sys.argv[0]))[0]
        #~ return os.path.join(pathname, 'binutils')

if __name__ == '__main__':
    # test code
    print getavgcoords(3, 0.15)


