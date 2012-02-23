<?php require_once('header.html'); ?>
	    <!-- #BeginEditable "content" -->
		<h1>Installation</h1>
		<h3>Windows</h3>
		<p>Grab the <a href="OlinMMInstall.exe">installer</a> and click next a few times.
		<h3>Linux</h3>
		<h4>Ubuntu</h4>
		<p>
			Grab the <a href="olinmm_1.1.0-1_all.deb">deb package</a> and double click it to install.
		</p>
		<h4>Other distributions</h4>
		We have an <b>untested</b> <a href="olinmm-1.1.0-2.noarch.rpm">RPM package</a> you can try.  Or you can download the <a href="olinmm_1.1.0-1.tar.gz">source archive</a> and just run the python file (OlinMM.py in the root directory).  You need to install <b>wxPython</b> first (python-wxgtk2.8).  You're on your own for startup on boot, but an easy way in Gnome is to go to System > Preferences > Sessions.  In KDE add a script that runs <b>OlinMM.py -m</b> in ~/.kde/Autostart.  The "-m" flag tells the map to start minimized.
		
		<h3>Mac</h3>
		<p>Grab the <a href="OlinMM.dmg">OS 10.5 application bundle</a> and throw it into Applications.  OS 10.4 is not currently supported, although the source archive should support it soon.</p>
			
		<!-- #EndEditable -->
	</div>
</body>
</html>
