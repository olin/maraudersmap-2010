"""
This is a setup.py script used to create a mac app

Usage:
    python setup.py py2app
"""

from setuptools import setup

APP = ['main.py']
DATA_FILES = ['demoIcon.png']

# The plist file specifies that there should be no icon in the dock 
# See: http://www.macosxtips.co.uk/index_files/disable-the-dock-icon-for-any-application.php
OPTIONS = {'argv_emulation': True, 'plist':'Info.plist'}

setup(
    app=APP,
    data_files=DATA_FILES,
    options={'py2app': OPTIONS},
    setup_requires=['py2app'],
)
