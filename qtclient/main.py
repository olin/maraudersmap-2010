#!/usr/bin/python
 
import sys
from PySide import QtCore
from PySide import QtGui

if __name__ == '__main__':
    app = QtGui.QApplication(sys.argv)
    promptStartup = False

    #TODO: Use http://www.dallagnese.fr/en/computers-it/recette-python-qt4-qsingleapplication-pyside/
    # To do sigle-instance checking

    #fileMenu = QtGui.QMenuBar.addMenu(QMenuBar.tr("&File"))

    # Create a Label and show it
    label = QtGui.QLabel("Hello World")
    label.show()
    # Enter Qt application main loop
    app.exec_()
    sys.exit()

