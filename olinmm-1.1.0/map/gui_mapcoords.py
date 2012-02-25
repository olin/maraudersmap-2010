import wx
import os, sys

class MapScrolledWindow(wx.Frame):
    def __init__(self, parent, id, title, nWhichMap, floor, fncallback, strPlacename):
        wx.Frame.__init__(self, parent, id, title, size=(1089, 764))
        self.fncallback = fncallback
        self.strPlacename = strPlacename
        self.nMapw = nWhichMap

        self.platformPath = ''
        if sys.platform.startswith('linux') and os.path.exists('/usr/bin/olinmm.py') and os.path.exists('/usr/share/olinmm'):
            self.platformPath = '/usr/share/olinmm/map/'

        if nWhichMap==1:
            strMapImage = self.platformPath + 'mm_01.png'
        else:
            # we are on the WH/EH map, which includes floors
            strMapImage = self.platformPath + 'mm_02_' + str(floor) + '.png'
            
        self.sw = wx.ScrolledWindow(self)
        strImagePath = getpath()
        bmp = wx.Image(os.path.join(strImagePath,strMapImage),wx.BITMAP_TYPE_PNG).ConvertToBitmap()
        staticBitmap = wx.StaticBitmap(self.sw, -1, bmp)
        self.sw.SetScrollbars(20, 20, 55, 40)
        
        self.Centre()
        
        staticBitmap.Bind(wx.EVT_LEFT_DOWN, self.OnClick)
        self.Show()

        self.thepoints = None, None
        
        self.SetCursor(wx.Cursor(os.path.join(strImagePath,self.platformPath +"p01.ico"), wx.BITMAP_TYPE_ICO, 10, 10))
        
        
    def OnClick(self, event):
        # add the person thingy to the bitmap
        strImagePath = getpath()
        bmp = wx.Image(os.path.join(strImagePath,self.platformPath + "p01.gif"),wx.BITMAP_TYPE_GIF).ConvertToBitmap()
        x, y = self.sw.CalcScrolledPosition(event.GetX(), event.GetY())
        
        xOffset = 0
        yOffset = 0
        
        if sys.platform.startswith('linux') or sys.platform.startswith('darwin'):
            xOffset = -5
            yOffset = -15
        personBitmap = wx.StaticBitmap(self.sw, -1, bmp, wx.Point(x+xOffset, y+yOffset))
        self.Refresh()
        res = wx.MessageBox("Is this point correct?", "Confirm placement", wx.YES_NO)
        if (res == wx.YES):
            self.thepoints = event.GetX(), event.GetY()
            self.fncallback(self.thepoints, self.strPlacename, self.nMapw)
            self.Close()
        else:
            personBitmap.Destroy()
        
    def getvalue(self):
        return self.thepoints, self.strPlacename, self.nMapw


if __name__=='__main__':
    app = wx.App()
    MapScrolledWindow(None, -1, 'Click on location...')
    app.MainLoop()

def getpath():
    pathname = os.path.split( os.path.abspath(sys.argv[0]))[0]
    return os.path.join(pathname, 'map')
