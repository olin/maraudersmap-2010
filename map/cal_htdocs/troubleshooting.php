<?php require_once('header.html'); ?>
	    <h1>Troubleshooting</h1>
	    <h3>
	    	When the Map updates, I receive a crash report three times in a row, and it fails to report my position.  I have a Olin Dell Laptop.
	    </h3>
	    <p>
	    	Your Intel ProSet Wireless drivers do not have WMI support enabled.  Go to Add/Remove programs in the Control Panel and find <b>Intel(R) PROSet/Wireless Software</b> and select <b>Change/Remove</b>.
	    </p>
	    <p>
	    	<img src="images/AddRemove.png" />
	    </p>
	    <hr />
		</p>
	    <p>
	    	Next, select <b>Modify</b>.
	    </p>
	    <p>
	    	<img src="images/ProSetModify.png" />
	    </p>
	    <p>
	    <hr />
	    <p>
	    	Find the <b>WMI Support</b> item and check it.  Click <b>Modify</b> and finish the wizard.  The map should now work.
	    </p>
	    <p>
	    	<img src="images/ProSetWMI.png" />
	    </p>
	    <!-- #EndEditable -->
	</div>
</body>
</html>
