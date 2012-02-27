#!/usr/bin/python
 
import sys
from PySide import QtCore
from PySide import QtGui

class Window(QtGui.QDialog):
    def __init__(self):
        super(Window, self).__init__()
        self.createActions()
        self.makeSysTray()

    def createActions(self):
        self.quitAction = QtGui.QAction("&Quit", self, triggered=QtGui.qApp.quit)

    def makeSysTray(self):
        self.menu = QtGui.QMenu(self)
        self.menu.addAction(self.quitAction)

        self.sysTray = QtGui.QSystemTrayIcon(self)
        self.sysTray.setContextMenu(self.menu)
        self.sysTray.show()



if __name__ == '__main__':
    app = QtGui.QApplication(sys.argv)

    
    if not QtGui.QSystemTrayIcon.isSystemTrayAvailable():
        print "Failed to detect presence of system tray, crashing"
        sys.exit(1)




    promptStartup = False
    window = Window()

    #TODO: Use http://www.dallagnese.fr/en/computers-it/recette-python-qt4-qsingleapplication-pyside/
    # To do sigle-instance checking

    #fileMenu = QtGui.QMenuBar.addMenu(QMenuBar.tr("&File"))

    # Create a Label and show it
    label = QtGui.QLabel("Hello World")
    label.show()
    # Enter Qt application main loop
    app.exec_()
    sys.exit()

