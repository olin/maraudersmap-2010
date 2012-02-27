#!/bin/bash

python setup.py py2app 
touch ./dist/main.app/Contents/Resources/qt.conf # Added to fix segfault; see http://www.thetoryparty.com/2009/03/03/pyqt4-i-hate-you/
