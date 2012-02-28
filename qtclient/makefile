all: main.app

# Mac OS X
main.app: main.py qt.conf setup.py Info.plist
	python setup.py py2app

# Removing
clean:
	rm -rf build/
	rm -rf dist/
