#!/usr/bin/env python

# Copyright 2007-2008, Andrew Barry, Benjamin Fisher
#
# This file is part of The Marauder's Map @ Olin.
#
# The Marauder's Map @ Olin is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License (Version 2, June 1991) as published by
# the Free Software Foundation.
#
# The Marauder's Map @ Olin is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

import wx
import wx.lib.newevent

import sys
import os

# in linux, we need to make sure we can import the rest
# of our files

# NOTE: If you are going to change this path you
# need to change it in data_connections.py as well.
# You also need to change it in the __init__ function here.
# (it's called self.SharePath)
# you also need to change the plath in map/gui_mapcoords.py
#
# The binutils path also needs to be set in coordinates.py

if sys.platform=='linux2':
	sys.path.append("/usr/share/olinmm")

import map
import binutils
import data_connections
import update
import time
import webbrowser
import base64

ID_REFRESH = 1001

ID_MAP = 1003
ID_LOCATION_YES = 1004
ID_LOCATION_NO = 1005
ID_COMBOLOCATION = 1006
ID_LOCATION_ENTRY = 1007
ID_TASKBAR_UPDATE = 1009

ID_TASKBAR_MAP = 1011
ID_TASKBAR_EXIT = 1012
ID_FILE_UPDATE = 1013
ID_FILE_CLOAK = 1014
ID_FILE_MAP = 1015
ID_OTHER = 1016
ID_TEXT_ROOM = 1017
ID_FILE_ICON = 1018
ID_FILE_SHOW = 1019

UpdateEvent, EVT_UPDATE_RETURN = wx.lib.newevent.NewEvent()

class MainWindow(wx.Frame):
	IsRunning = True
	
	def __init__(self, parent, id, title):
		wx.Frame.__init__(self,parent,id,title, wx.DefaultPosition, wx.Size(300,300), wx.MINIMIZE_BOX | wx.SYSTEM_MENU | wx.CAPTION | wx.CLOSE_BOX | wx.CLIP_CHILDREN)
		
		if sys.platform=='linux2' and os.path.exists('/usr/bin/olinmm.py') and os.path.exists('/usr/share/olinmm'):
			self.SharePath = '/usr/share/olinmm/'
		else:
			self.SharePath = ''
		
		self.strUsername = binutils.getusername()
		self.strPlacename = None
		self.nMapx = self.nMapy = self.nMapw= None
		
		# file menu bar
		filemenu=wx.Menu()
		helpmenu = wx.Menu()
		helpmenu.Append(wx.ID_ABOUT, "About")

		if sys.platform == 'darwin':
			filemenu.Append(ID_FILE_SHOW, "&Show Window")
		filemenu.Append(ID_FILE_UPDATE, "&Update\tCtrl+U")
		filemenu.Append(ID_FILE_MAP, "&Map\tCtrl+M")
		filemenu.AppendSeparator()
		filemenu.Append(ID_FILE_ICON, "Personalize &Icon")
		filemenu.AppendSeparator()
		filemenu.Append(ID_FILE_CLOAK, "&Remove from Map and Exit")
		filemenu.Append(wx.ID_EXIT, "E&xit")
		self.menuBar = wx.MenuBar()
		self.menuBar.Append(filemenu, "&File")
		self.menuBar.Append(helpmenu, "&Help")
		
		self.SetMenuBar(self.menuBar)
		
		self.panelSizer = wx.BoxSizer(wx.VERTICAL)
		
		self.mainSizer = wx.BoxSizer(wx.VERTICAL)

		
		self.panel = wx.Panel(self)
		self.panelSizer.Add(self.panel, 1, wx.EXPAND)
		
		self.usernameSizer = wx.BoxSizer(wx.HORIZONTAL)
		self.userLabel = wx.StaticText(self.panel, wx.ID_ANY, "Unknown user")
		self.upToNoGood = wx.CheckBox(self.panel, wx.ID_ANY, "Up to No Good")
		self.upToNoGood.SetWindowStyle(wx.ALIGN_RIGHT)
		self.upToNoGood.SetValue(True)
		self.upToNoGood.Enable(False)

		self.usernameSizer.Add(self.userLabel, 1, wx.ALL | wx.ALIGN_LEFT, 10)
		self.usernameSizer.Add(self.upToNoGood, 0, wx.ALIGN_RIGHT | wx.ALL, 10)
		
		self.staticLine1 = wx.StaticLine(self.panel)

		self.lblBusy = wx.StaticText(self.panel, wx.ID_ANY, "Welcome to MM@Olin")
		self.lblBusy.SetWindowStyle(wx.ALIGN_CENTRE)
				
		# locations
		self.locationSizer = wx.BoxSizer(wx.HORIZONTAL)
		self.lblYouAre = wx.StaticText(self.panel, wx.ID_ANY, "You are in ")
		#self.butYes = wx.Button(self.panel, ID_LOCATION_YES, "Yes", wx.DefaultPosition, wx.Size(33, 22))
		self.butYes = wx.Button(self.panel, ID_LOCATION_YES, "Yes", wx.DefaultPosition, wx.DefaultSize, wx.BU_EXACTFIT)
		
		#self.butNo = wx.Button(self.panel, ID_LOCATION_NO, "No", wx.DefaultPosition, wx.Size(33, 22))
		self.butNo = wx.Button(self.panel, ID_LOCATION_NO, "No", wx.DefaultPosition, wx.DefaultSize, wx.BU_EXACTFIT)
		
		self.locationSizer.Add(self.lblBusy, 1, wx.ALIGN_CENTER | wx.ALIGN_CENTER_HORIZONTAL | wx.ALL, 10)
		
		self.locationSizer.Add(self.lblYouAre, 1, wx.ALL | wx.EXPAND, 10)
		self.locationSizer.Add(self.butYes, 0, wx.LEFT | wx.TOP | wx.BOTTOM, 10)
		self.locationSizer.Add(self.butNo, 0, wx.ALL, 10)
		
		#self.butEntryOk = wx.Button(self.panel, ID_LOCATION_ENTRY, "OK", wx.DefaultPosition, wx.Size(40, 22))
		self.butEntryOk = wx.Button(self.panel, ID_LOCATION_ENTRY, "OK", wx.DefaultPosition, wx.DefaultSize, wx.BU_EXACTFIT)
		#self.butOther = wx.Button(self.panel, ID_OTHER, "Other", wx.DefaultPosition, wx.Size(40, 22))
		self.butOther = wx.Button(self.panel, ID_OTHER, "Other", wx.DefaultPosition, wx.DefaultSize, wx.BU_EXACTFIT)
		self.comboLocation = wx.Choice(self.panel, ID_COMBOLOCATION)

		self.locationSizer.Add(self.comboLocation, 1, wx.EXPAND | wx.ALL, 10)
		self.locationSizer.Add(self.butEntryOk, 0, wx.RIGHT | wx.TOP | wx.BOTTOM, 10)
		self.locationSizer.Add(self.butOther, 0, wx.RIGHT | wx.TOP | wx.BOTTOM, 10)
		
		self.locationSizer.Hide(self.lblYouAre)
		self.locationSizer.Hide(self.butYes)
		self.locationSizer.Hide(self.butNo)
		
		self.locationSizer.Hide(self.comboLocation)
		self.locationSizer.Hide(self.butEntryOk)
		self.locationSizer.Hide(self.butOther)
		
		# bottom navigation buttons
		self.buttonSizer = wx.BoxSizer(wx.HORIZONTAL)
		
		self.butRefresh = wx.Button(self.panel, ID_REFRESH, "Update")
		self.butMap = wx.Button(self.panel, ID_MAP, "Map")
		
		#self.buttonSizer.AddStretchSpacer()
		self.buttonSizer.Add(wx.Size(0, 0), 1)
		self.buttonSizer.Add(self.butRefresh, 0, wx.ALL, 10)
		self.buttonSizer.Add(self.butMap, 0, wx.ALL, 10)
		self.buttonSizer.Add(wx.Size(0, 0), 1)
		#self.buttonSizer.AddStretchSpacer()
		
		# main sizer setup
		self.mainSizer.Add(self.usernameSizer, 0, wx.EXPAND | wx.ALIGN_CENTER_HORIZONTAL)
		self.mainSizer.Add(self.staticLine1, 0, wx.EXPAND | wx.LEFT | wx.RIGHT, 10)
		self.mainSizer.Add(self.locationSizer, 0, wx.EXPAND)
		self.mainSizer.Add(self.buttonSizer, 0, wx.EXPAND | wx.ALIGN_CENTER_HORIZONTAL)
		
		self.SetSizer(self.panelSizer)
		self.panel.SetSizer(self.mainSizer)
		self.SetAutoLayout(True)
		
		self.mainSizer.Fit(self.panel)
		self.panelSizer.Fit(self)		
		self.panelSizer.SetSizeHints(self)
		
		# thread handling event
		
		EVT_UPDATE_RETURN(self, self.OnUpdateReturn)
		wx.EVT_MENU(self, wx.ID_ABOUT, self.OnAbout)
		wx.EVT_MENU(self, wx.ID_EXIT, self.OnTaskbarExit)
		wx.EVT_MENU(self, ID_FILE_CLOAK, self.OnCloakAndExit)
		wx.EVT_MENU(self, ID_FILE_ICON, self.OnIcon)
		
		wx.EVT_MENU(self, ID_FILE_UPDATE, self.OnRefresh)
		wx.EVT_MENU(self, ID_FILE_MAP, self.OnMap)

		if sys.platform == 'darwin':
			wx.EVT_MENU(self, ID_FILE_SHOW, self.OnMacShow)
		
		wx.EVT_BUTTON(self, ID_REFRESH, self.OnRefresh)
		wx.EVT_BUTTON(self, ID_MAP, self.OnMap)

		
		wx.EVT_BUTTON(self, ID_LOCATION_YES, self.OnLocationYes)
		wx.EVT_BUTTON(self, ID_LOCATION_NO, self.OnLocationNo)
		wx.EVT_BUTTON(self, ID_LOCATION_ENTRY, self.OnLocationEntry)
		wx.EVT_BUTTON(self, ID_OTHER, self.OnNewPoint)
		
		wx.EVT_ICONIZE(self, self.OnMinimize)
		
		wx.EVT_CLOSE(self, self.OnExit)
		
		f=open(self.SharePath + 'host.txt','r')
		self.g_strHost = f.read()
		f.close()
		
		#set the frame icon
		# we make the icon a memeber of the class so we can reuse
		# it later when changing the tooltip
		if sys.platform == 'darwin':
			self.mmOlinIconMac = wx.Icon("olin_mac.ico", wx.BITMAP_TYPE_ANY)
			self.SetIcon(self.mmOlinIconMac)
		else:
			self.mmOlinIcon = wx.IconBundle()
			self.mmOlinIcon.AddIconFromFile(self.SharePath + "olin.ico", wx.BITMAP_TYPE_ANY)
			self.SetIcons(self.mmOlinIcon);
		# set up the taskbar icon
		self.taskBarIcon = MapTaskBar()
		self.taskBarIcon.SetFrame(self)
		self.taskBarIcon.EnableUpdating()
		
		self.UpdateTaskbarIcon("MM@Olin")
		
		global promptStartup
		#check for first-start in linux or mac
		if promptStartup == True:
			startupDialog = wx.MessageDialog(self, "Welcome new Marauder's Map user!\n\nWould you like to automatically start the map on login?", "Welcome to MM@Olin", wx.YES_NO)
			if startupDialog.ShowModal() == wx.ID_YES:

				if sys.platform == 'linux2':
					# check to see if this is KDE or Gnome
					if os.path.exists(wx.GetHomeDir() + "/.gnome2"):
						o = os.popen("mkdir -p " + wx.GetHomeDir() + "/.config/autostart")
						o.close()
						o = os.popen("cp " + self.SharePath + "olinmm.py.desktop " + wx.GetHomeDir() + "/.config/autostart")
						o.close()
					
					if os.path.exists(wx.GetHomeDir() + "/.kde"):
						o = os.popen("mkdir -p " + wx.GetHomeDir() + "/.kde/Autostart")
						o.close()
						o = os.popen("cp " + self.SharePath + "olinmmstart " + wx.GetHomeDir() + "/.kde/Autostart")
						o.close()
				elif sys.platform == 'darwin':
					o = os.popen("/usr/bin/osascript -e 'tell application \"System Events\" to make new login item with properties { path: \"/Applications/OlinMM.app\", hidden:true } at end'")
					o.close()
		
		self.taskBarIcon.Bind(wx.EVT_TASKBAR_LEFT_DCLICK, self.OnTaskbarDClick)
		self.updateTimer = UpdateTimer()
		self.updateTimer.SetFrame(self)
		self.updateTimer.Start(600000)
		self.OnThreadRefresh()
	
	def UpdateTaskbarIcon(self,string):
		if sys.platform == 'darwin':
			self.taskBarIcon.SetIcon(self.mmOlinIconMac, string)
		else:
			self.taskBarIcon.SetIcon(self.mmOlinIcon.GetIcon(wx.Size(16,16)), string)
	
	def OnAbout(self,event):
		d= wx.MessageDialog( self, "Messrs Barry and Fisher, purveyors of aids to magical mischief-makers, are proud to present the Marauders Map.\nv. 1.1\n2007-2008", "About MM@Olin", wx.OK)
		d.ShowModal() # Shows it
		d.Destroy() # finally destroy it when finished.
	
	def OnIcon(self,event):
		# Open website in a browser
		url = self.g_strHost + '/upload.php?username=' + base64.b64encode(self.strUsername)
		webbrowser.open(url);
		
	def OnCloakAndExit(self, event):
		# there is a problem in that if you remove yourself from the map
		# while there is an update going on you will be re-added as soon as the
		# update finishes
		if (self.butRefresh.GetLabel() == "Updating..."):
			wx.MessageBox("Please wait until the update completes before removing yourself.", "MM@Olin")
			return
			
		d = wx.MessageDialog(self, "Remove yourself from the map and quit?", "MM@Olin", wx.YES_NO)
		if (d.ShowModal() == wx.ID_YES):
			flag, result = update.do_cloak(self.strUsername)
			d.Destroy()
			if (flag == True):
				self.IsRunning = False
				self.updateTimer.Stop()
				self.taskBarIcon.RemoveIcon()
				self.Destroy()
				app.ExitMainLoop()
			else:
				self.showBusy('Error: ' + result)
	
	def OnExit(self,event):
		if (event.CanVeto() == True):
			# only minimize to the taskbar, don't close
			self.Show(False)
		else:
			self.IsRunning = False
			self.updateTimer.Stop()
			self.taskBarIcon.RemoveIcon()
			self.Destroy()
			app.ExitMainLoop()
			
	def OnTaskbarExit(self, event):
		self.IsRunning = False
		self.updateTimer.Stop()
		self.taskBarIcon.RemoveIcon()
		self.Close(True)
		app.ExitMainLoop()
		
	def OnTaskbarDClick(self, event):
		if (self.IsShown() == True):
			self.Show(False)
		else:
			self.Show(True)
			self.Raise()
			self.Iconize(False)
		
	def OnMinimize(self, event):
		if (event.Iconized()):
			self.Show(False)
		else:
			#self.Iconized(False)
			self.UpdateSizers()
			self.Show(True)		
			
	def OnMacShow(self, event):
		self.Show(True)

	def OnRefresh(self, event):
		#hide old refresh data that the user might not have acted on
		self.locationSizer.Hide(self.lblYouAre)
		self.locationSizer.Hide(self.butYes)
		self.locationSizer.Hide(self.butNo)
		
		self.locationSizer.Hide(self.comboLocation)
		self.locationSizer.Hide(self.butEntryOk)
		self.locationSizer.Hide(self.butOther)
		
		self.locationSizer.Layout()
		self.mainSizer.Layout()
	
		self.taskBarIcon.DisableUpdating()
		self.menuBar.Enable(ID_FILE_UPDATE, False)
		
		txtStatus = ""
		
		self.butRefresh.Enable(False)
		self.butRefresh.SetLabel("Updating...")
		self.showBusy("Updating...")
		makeThread(self.DoUpdate, "nothing")
		
	def OnMap(self, event):
		# Open website in a browser
		url = self.g_strHost + '/ui/mapui.php'
		webbrowser.open(url);

	def SetUser(self, newUser):
		self.userLabel.SetLabel(newUser)
		
	def showBusy(self, s):
		self.lblBusy.SetLabel(s)
		
		self.locationSizer.Hide(self.lblYouAre)
		self.locationSizer.Hide(self.butYes)
		self.locationSizer.Hide(self.butNo)
		
		self.locationSizer.Show(self.lblBusy)
		
		self.locationSizer.Hide(self.comboLocation)
		self.locationSizer.Hide(self.butEntryOk)
		self.locationSizer.Hide(self.butOther)
		
		# Set this on the icon as well
		self.UpdateTaskbarIcon(s)
		self.UpdateSizers()
	
	def ShowLocation(self, string):
		
		self.lblYouAre.SetLabel(string)
		
		self.locationSizer.Hide(self.lblBusy)
		
		self.locationSizer.Show(self.lblYouAre)
		self.locationSizer.Show(self.butYes)
		self.locationSizer.Show(self.butNo)
		
		self.locationSizer.Hide(self.comboLocation)
		self.locationSizer.Hide(self.butEntryOk)
		self.locationSizer.Hide(self.butOther)
		
		# Set this on the icon as well
		self.UpdateTaskbarIcon(string)
		self.UpdateSizers()
		
	
	def UpdateSizers(self):
		self.locationSizer.Layout()
		self.buttonSizer.Layout()
		self.mainSizer.Layout()
		
		self.mainSizer.Fit(self.panel)
		self.panelSizer.Fit(self)
		self.panelSizer.SetSizeHints(self)
		self.SetSize(self.GetMinSize())
		self.Refresh()
		
	def OnLocationYes(self, event):
		# Confirm that this is the right location
		flag, ret = update.do_train(self.strUsername, self.strPlacename, self.nMapx, self.nMapy,self.nMapw, self.currentData)
		if not flag:
			self.showBusy('Error: '+ret)
		else:
			self.showBusy('You are ' + self.ParseLocation(self.strPlacename + "."))
			self.HideLocationControls()
	
	def OnLocationNo(self, event):
		#Clear combo box first.
		self.comboLocation.Clear()
		
		#populate the combo box with the hints
		for o in self.locationGuess:
			placeStr = self.ParseLocation(o[0])
			tmpChar = placeStr[0].capitalize()
			placeStr = placeStr[1:]
			placeStr = tmpChar + placeStr
			
			self.comboLocation.Append(placeStr, o[0])
		if len(self.locationGuess)>=2: self.comboLocation.SetSelection(1)
	
		self.locationSizer.Hide(self.lblYouAre)
		self.locationSizer.Hide(self.butYes)
		self.locationSizer.Hide(self.butNo)
		
		self.locationSizer.Show(self.comboLocation)
		self.locationSizer.Show(self.butEntryOk)
		self.locationSizer.Show(self.butOther)
		
		self.UpdateSizers()
		
	def OnNewPoint(self, event):
		dialog = DialogSetPoint(self, wx.ID_ANY, "Create New Point")
		newPlacename = self.strPlacename.replace("OC", "MH")
		dialog.comboBuilding.SetStringSelection(newPlacename[0:2])
		dialog.spinFloor.SetValue(int(self.strPlacename[2]))
		
		if (dialog.ShowModal() == wx.ID_OK):
			description = dialog.txtRoom.GetValue()
			# convert "Room309" to "rm309", "309" to "rm309", etc.
			# do this by checking to see if the first two characters are integers
			try:
				if (int(description[0:1]) != 0):
					#this is just an integer (even could be 309e for a suite) -- must be a room number
					description = "rm" + description
			except:
				pass
				#nothing
			if (description[0:2] == "AC" or description[0:2] == "CC" or description[0:2] == "OC" or description[0:2] == "MH" or description[0:2] == "EH" or description[0:2] == "WH"):
				# this has a room in it
				description = description.replace("AC", "rm")
				description = description.replace("CC", "rm")
				description = description.replace("OC", "rm")
				description = description.replace("MH", "rm")
				description = description.replace("EH", "rm")
				description = description.replace("WH", "rm")
				description = description.replace(" ", "")
				description = description.replace(" ", "")
		
			strOut = dialog.comboBuilding.GetStringSelection() + str(dialog.spinFloor.GetValue()) + "0,"
			if (dialog.checkInside.GetValue() == True):
				strOut += "in,"
			else:
				strOut +="out,"
				
			strOut += description
			strOut = strOut.replace("MH", "OC")
			
			# see if we can find building in the string
			if strOut.startswith("AC") or strOut.startswith("OC")  or strOut.startswith("CC"):
				mapw = 1
			elif strOut.startswith("WH")  or strOut.startswith("EH"):
				mapw = 2
			else:
				wx.MessageBox('Error: Your new data point does not include a building name.')
				return
			
			#Create a new data point. (Note that we pass a reference to newDataPointCallback).
			map.MapScrolledWindow(self, -1, 'Click on location of \"'+ self.ParseLocation(strOut) + '\"', mapw, dialog.spinFloor.GetValue(), self.newDataPointCallback, strOut)

	def HideLocationControls(self):
		
		self.locationSizer.Hide(self.lblYouAre)
		self.locationSizer.Hide(self.butYes)
		self.locationSizer.Hide(self.butNo)
		
		self.locationSizer.Hide(self.comboLocation)
		self.locationSizer.Hide(self.butEntryOk)
		
		self.UpdateSizers()
		
	def OnLocationEntry(self, event):
		# Check if the name is another placename, or something entirely different
		strPlacename = self.comboLocation.GetClientData(self.comboLocation.GetSelection()).strip()
		print strPlacename
		if not strPlacename:
			return
			
		for place in self.locationGuess:
			if strPlacename == place[0]:
				self.strPlacename = place[0]
				self.nMapx,self.nMapy,self.nMapw  = place[2],place[3],place[4]
				# Send training data
				flag, ret = update.do_train(self.strUsername, self.strPlacename, self.nMapx, self.nMapy,self.nMapw, self.currentData)
				if not flag: self.showBusy('Error training: '+ret)
				else: self.showBusy('You are ' + self.ParseLocation(strPlacename + "."))
				
				self.HideLocationControls() #Hide controls...
				return
				
		self.HideLocationControls()
		
	def newDataPointCallback(self, pts, strPlacename, mapw):
		self.nMapx, self.nMapy, self.nMapw = pts[0], pts[1], mapw
		self.strPlacename = strPlacename
		
		# Send training data
		flag, ret = update.do_train(self.strUsername, strPlacename, self.nMapx, self.nMapy,self.nMapw, self.currentData)
		if not flag: 
			self.showBusy('Error training: '+ret)
			return
		self.showBusy('Created point '+ self.ParseLocation(strPlacename))
		
	def OnThreadRefresh(self):
		self.OnRefresh(wx.CommandEvent())
		
	def DoUpdate(self, nothing):
		global delayRefresh
		if delayRefresh == True:
			time.sleep(15)
			delayRefresh = False
		flag, ret, data = update.do_update(self.strUsername, self.strPlacename, "")
		
		# since we are running in a thread, Linux will have problems updating the GUI
		# if we try to do it from here -- so post an event and let the event handler take care
		# of the rest
		event = UpdateEvent(attr1=flag, attr2=ret, attr3=data)
		wx.PostEvent(self, event)
		
		
	def OnUpdateReturn(self, event):
		#make sure the frame hasn't already closed
		# we don't have to do this anymore because we are an event
		#if (self.IsRunning == True):
		
		flag = event.attr1
		ret = event.attr2
		data = event.attr3
		
		self.menuBar.Enable(ID_FILE_UPDATE, True)
		self.taskBarIcon.EnableUpdating()
		self.butRefresh.SetLabel("Update")
		self.butRefresh.Enable(True)
		if not flag:
			self.showBusy('Error: '+ret)
			self.strPlacename = None
			return
		
		self.currentData = data #Wireless vector
		self.locationGuess = ret #Guesses as to location
		self.strPlacename = ret[0][0]
		self.nMapx, self.nMapy, self.nMapw = ret[0][2],ret[0][3],ret[0][4]

		location = self.ParseLocation(self.strPlacename)

		if location == None:
			return
		self.ShowLocation("Are you " + location + "?")
		#hide the upper label
		self.mainSizer.Hide(self.lblBusy)
		
		self.mainSizer.Fit(self.panel)
		self.mainSizer.Layout()
		self.panelSizer.Fit(self)
		self.panelSizer.SetSizeHints(self)
		self.SetSize(self.GetMinSize())
		self.Update()

	def ParseLocation(self, location):
		# location strings look like WH,in,rm309
		location = location.replace("OC", "MH")
		try:
			building, inside, description = location.split(",")
		except:
			wx.MessageBox("Error: Failed to parse location string: " + location, "MM@Olin Error")
			return None
		
		floor = building[2]
		building = building[0:2]
		
		if (inside == "in"):
			inside = "inside"
		elif (inside == "out"):
			inside = "outside of"
	
		if (floor == "1"):
			floor = "1st"
		elif (floor == "2"):
			floor = "2nd"
		elif (floor == "3"):
			floor = "3rd"
		elif (floor == "4"):
			floor = "4th"
		elif (floor == "0"):
			floor = "LL"
		
			
		if (description.find("rm") != -1 or description.find("room") != -1 or description.find("Room") != -1 or description.find("ROOM") != -1):
			# this has a room
			description = description.replace("rm", "")
			description = description.replace("room", "")
			description = description.replace("Room", "")
			description = description.replace("ROOM", "")
			
			location = inside + " " + building + description
		else:
			if (floor != "LL"):
				location = inside + " " + building + " " + floor + " floor " + description
			else:
				location = inside + " " + building + " (" + floor + ") " + description
		
		return location

class MapTaskBar(wx.TaskBarIcon):
	def CreatePopupMenu(self):
		menu = wx.Menu()
		if (self.updating == True):
			menu.Append(ID_TASKBAR_UPDATE, "Updating...")
			menu.Enable(ID_TASKBAR_UPDATE, False)
		else:
			menu.Append(ID_TASKBAR_UPDATE, "Update")
		menu.Append(ID_TASKBAR_MAP, "Map")
		menu.Append(ID_TASKBAR_EXIT, "Exit")
		
		wx.EVT_MENU(self, ID_TASKBAR_UPDATE, frame.OnRefresh)
		wx.EVT_MENU(self, ID_TASKBAR_MAP, frame.OnMap)
		wx.EVT_MENU(self, ID_TASKBAR_EXIT, frame.OnTaskbarExit)
		return menu
		
	def SetFrame(self, newFrame):
		self.frame = newFrame
	
	def DisableUpdating(self):
		self.updating = True
	
	def EnableUpdating(self):
		self.updating = False

class DialogSetPoint(wx.Dialog):
	def __init__(self, parent, id, title):
		wx.Dialog.__init__(self, parent, id, title)
		
		self.building = ""
		self.floor = 1
		self.inside = True
		self.room = ""
		
		self.panelSizer = wx.BoxSizer(wx.VERTICAL)
		self.panel = wx.Panel(self)
		self.panelSizer.Add(self.panel, 1, wx.EXPAND)
		
		self.mainSizer = wx.BoxSizer(wx.VERTICAL)
		self.controlSizer = wx.FlexGridSizer(2, 4, 2, 2)
		self.buttonSizer = wx.BoxSizer(wx.HORIZONTAL)
		
		# user wx.Choice instead of wx.ComboBox so it loks good in Linux
		self.comboBuilding = wx.Choice(self.panel, wx.ID_ANY, wx.DefaultPosition, wx.DefaultSize, ["AC", "CC", "EH", "MH", "WH"])
		
		self.spinFloor = wx.SpinCtrl(self.panel, wx.ID_ANY, wx.EmptyString, wx.DefaultPosition, wx.Size(45, 22), wx.SP_ARROW_KEYS, 0, 4, 1)
		self.txtRoom = wx.TextCtrl(self.panel, ID_TEXT_ROOM, "", wx.DefaultPosition, wx.DefaultSize, wx.TE_PROCESS_ENTER)
		self.checkInside = wx.CheckBox(self.panel, wx.ID_ANY, "Inside Room")
		self.checkInside.SetValue(True)
		self.butOk = wx.Button(self.panel, wx.ID_OK, "OK")
		self.butCancel = wx.Button(self.panel, wx.ID_CANCEL, "Cancel")
		
		#labels
		self.lblBuilding = wx.StaticText(self.panel, wx.ID_ANY, "Building")
		self.lblFloor = wx.StaticText(self.panel, wx.ID_ANY, "Floor\n(LL = 0)")
		self.lblRoom = wx.StaticText(self.panel, wx.ID_ANY, "Room # or description\n(ex. \"309\" or \"316A\" or \"Library\")")
		
		self.controlSizer.Add(self.lblBuilding, 0, wx.RIGHT | wx.LEFT | wx.TOP | wx.ALIGN_CENTER_VERTICAL, 10)
		self.controlSizer.Add(self.lblFloor, 0, wx.RIGHT | wx.LEFT | wx.TOP | wx.ALIGN_CENTER_VERTICAL, 10)
		self.controlSizer.Add(self.lblRoom, 0, wx.RIGHT | wx.LEFT | wx.TOP | wx.ALIGN_CENTER_VERTICAL, 10)
		#self.controlSizer.AddStretchSpacer()
		self.controlSizer.Add(wx.Size(0, 0), 1)
		
		self.staticLine = wx.StaticLine(self.panel)
		
		self.controlSizer.Add(self.comboBuilding, 0, wx.RIGHT | wx.LEFT, 10)
		self.controlSizer.Add(self.spinFloor, 0, wx.RIGHT | wx.LEFT, 10)
		self.controlSizer.Add(self.txtRoom, 1, wx.EXPAND | wx.RIGHT | wx.LEFT, 10)
		self.controlSizer.Add(self.checkInside, 0, wx.RIGHT | wx.LEFT, 10)
		
		self.buttonSizer.Add(self.butOk, 0, wx.ALL, 10)
		self.buttonSizer.Add(self.butCancel, 0, wx.ALL, 10)
		
		self.mainSizer.Add(self.controlSizer, 0)
		self.mainSizer.Add(self.staticLine, 1, wx.EXPAND | wx.TOP | wx.LEFT | wx.RIGHT, 10)
		self.mainSizer.Add(self.buttonSizer, 0, wx.CENTER)
		
		self.txtRoom.SetFocus()
		
		self.SetSizer(self.panelSizer)
		self.panel.SetSizer(self.mainSizer)
		self.SetAutoLayout(True)
		
		
		wx.EVT_BUTTON(self, wx.ID_OK, self.OnOk)
		wx.EVT_TEXT_ENTER(self, ID_TEXT_ROOM, self.OnOk)
		
		self.mainSizer.Fit(self.panel)
		self.panelSizer.Fit(self)		
		self.panelSizer.SetSizeHints(self)
		
	def OnOk(self, event):
		flag = True
		if self.spinFloor.GetValue() == 0 and self.comboBuilding.GetStringSelection() != "MH":
			wx.MessageBox('Error: That building doesn\'t have a lower level.')
			flag = False
			
		if (self.txtRoom.GetValue() == ""):
			wx.MessageBox("Please enter a location description.", "MM@Olin")
			flag = False
		
		if flag == True:
			self.EndModal(wx.ID_OK)
	
class UpdateTimer(wx.Timer):
	def SetFrame(self, newFrame):
		frame = newFrame
	def Notify(self):
		frame.OnThreadRefresh()
		
def makeThread(fn, args):
	import threading
	class MyThread(threading.Thread):
		"""this is a wrapper for threading.Thread that improves
		the syntax for creating and starting threads.
		"""
		def __init__(self, target, *args):
			threading.Thread.__init__(self, target=target, args=args)
			self.start()
	MyThread(fn, args)
	
app = wx.PySimpleApp()
promptStartup = False
#suppress a "Deleted stale lock file" error
logNo = wx.LogNull()

# create a class of wxSingleInstanceChecker so we can make sure to only have one client
# running at a time

#in linux we make the process called ".MMOlin" so the file will be hidden
if sys.platform=='linux2':
	
	# deb packages have some trouble with installing to a user's home directory, because they
	# are installed as root, so we have to work around that by doing some work now.
	
	# add the startup file, if this is the first time we have run
	if os.path.exists(wx.GetHomeDir() + "/.olinmm") == False:
		#first run
		o = os.popen("mkdir " + wx.GetHomeDir() + "/.olinmm");
		res = o.read()
		o.close()
		if os.path.exists(wx.GetHomeDir() + "/.config/autostart/olinmm.py.desktop") == False and os.path.exists(wx.GetHomeDir() + "/.kde/Autostart/olinmmstart") == False:
			promptStartup = True
		
	singleInstanceChecker = wx.SingleInstanceChecker("MMOlin", wx.GetHomeDir() + "/.olinmm")
	
elif sys.platform=='darwin':
	singleInstanceChecker = wx.SingleInstanceChecker(".MMOlin")	
else:
	singleInstanceChecker = wx.SingleInstanceChecker("MMOlin")

if sys.platform=='darwin':
	#check to see if this is the first run
	macConfig = wx.Config("MMOlin")
	if macConfig.HasEntry("firstrun") == False:
		macConfig.Write("firstrun", "Y")
		promptStartup = True
if (singleInstanceChecker.IsAnotherRunning() == True):
	wx.MessageBox("Another instance of MM@Olin is already running.  Check your taskbar to see if it was minimized.", "MM@Olin")
else:
	# check the command line arguments to see if we should start in the
	# task tray
	startMin = False
	delayRefresh = False
	for arg in sys.argv:
		if (arg == "-m"):
			#this is minimized
			startMin = True
			delayRefresh = True
			# in linux, we can have trouble starting on a panel if the panel doesn't exist,
			# so wait for a few seconds
			if sys.platform=='linux2':
				time.sleep(5)
			
	frame = MainWindow(None, wx.ID_ANY, "MM@Olin")
	frame.SetUser(frame.strUsername)
	
	if sys.platform == 'darwin' and promptStartup == False:
		startMin = True

	if startMin == False:
		frame.Show(True)

	app.MainLoop()



