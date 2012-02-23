<?php require_once('header.html'); ?>
	    <!-- #BeginEditable "content" -->
	    <h1>Personalize Icon</h1>

<?
require('lib_common.php');

$flag = true;

if (isset($_FILES["photo"]["name"]) && !isset($_POST["restoreDefault"])) {
	if (isset($_FILES["photo"]["error"]) && $_FILES["photo"]["error"] != "") {
		//error
		echo "<b>There was an error uploading your file.  Make sure that the file is
			under 20 kB in size and that the file name does not contain a space.</b><br><br>";
	} else
	{
		//file ok
		$flag = false;
				
		//check to make sure that the username is set
		if (!isset($_POST["username"]) || $_POST["username"] == "")
		{
			echo "<b>Error: Username not set.</b>";
		} else
			{


			$newFileName = "usericons/" . base64_decode($_POST["username"]) . ".gif";
			
		
			//file is in images directory
		
			//resize file
			if (checkImage($_FILES["photo"]["tmp_name"]) == false)
			{
				echo "Oops!  Your image isn't 11x21 (that's width = 11, height = 21) or it is animated.<br><br>";
				$flag = true;
			} else
			{
		
				//thumbnail has been created... add it to the database

				move_uploaded_file($_FILES["photo"]["tmp_name"], $newFileName);
				
				//connect to the database
				DBConnect();
				$sql = "UPDATE usercal SET icon='" . DBFix($newFileName) . "' WHERE username='" . DBFix(base64_decode($_POST["username"])) . "'";
				$result = DB($sql);
				echo "<b>Icon Uploaded Successfully.</b>";
			}
		}
	}
}

// check for the restore default
if (isset($_POST["restoreDefault"]))
{
	//check to make sure that the username is set
	if (!isset($_POST["username"]) || $_POST["username"] == "")
	{
		echo "<b>Error: Username not set.</b>";
	} else
	{
		DBConnect();
		$sql = "UPDATE usercal SET icon='' WHERE username='" . DBFix(base64_decode($_POST["username"])) . "'";
		$result = DB($sql);
		echo "<b>Icon Restored.</b>";
		$flag = false;
	}
}

if ($flag == true) {
	echo "<b>Please Select a GIF to Upload.</b><br><br><i>Note that only single-frame GIF type files
		sized 11x21 (that's width = 11, height = 21) are acceptable.</i><br><br>";
	echo "<!-- The data encoding type, enctype, MUST be specified as below -->
    <form enctype=\"multipart/form-data\" action=\"" . $PHP_SELF . "\" method=\"POST\">
      <!-- MAX_FILE_SIZE must precede the file input field -->
      <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"20000\" />
      <input name=\"photo\" type=\"file\" />
      <input type=\"hidden\" name=\"username\" value=\"" . $_GET["username"] . "\">
      <input type=\"submit\" value=\"Upload Image\" />
      <input type=\"submit\" name=\"restoreDefault\" value=\"Restore Default Image\" />
    </form>";
    echo "<p>Here are some icons you might want to start with:</p>
    	<p>
    	<img src=\"images/p01.gif\" style=\"padding:0px 10px 0px 10px\">
		<img src=\"images/p02.gif\" style=\"padding:0px 10px 0px 10px\">
		<img src=\"images/p03.gif\" style=\"padding:0px 10px 0px 10px\">
		<img src=\"images/p04.gif\" style=\"padding:0px 10px 0px 10px\">
		</p>";
}

function checkImage($filename)
{
	list($width, $height) = getimagesize($filename);

	if ($width > 11 || $height > 21) {
		return false;
	}
	return !isAnimated($filename);
}


function isAnimated($filename)
{
        $filecontents=file_get_contents($filename);

        $str_loc=0;
        $count=0;
        while ($count < 2) # There is no point in continuing after we find a 2nd frame
        {

                $where1=strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
                if ($where1 === FALSE)
                {
                        break;
                }
                else
                {
                        $str_loc=$where1+1;
                        $where2=strpos($filecontents,"\x00\x2C",$str_loc);
                        if ($where2 === FALSE)
                        {
                                break;
                        }
                        else
                        {
                                if ($where1+8 == $where2)
                                {
                                        $count++;
                                }
                                $str_loc=$where2+1;
                        }
                }
        }

        if ($count > 1)
        {
                return(true);

        }
        else
        {
                return(false);
        }
}

exec("ls *gif" ,$allfiles);
foreach ($allfiles as $thisfile)
{
        if (is_ani($thisfile))
        {
                echo "$thisfile is animated<BR>\n";
        }
        else
        {
                echo "$thisfile is NOT animated<BR>\n";
        }
}


?>







	    <!-- #EndEditable -->
	</div>
</body>
</html>
