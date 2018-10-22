<?php

	header("Refresh: 600");

	require_once('../shared/params.php');	 
	require_once('../shared/manage_keys.php');		 

	$key = create_key();			 

	// 1. DELETE FILES OLDER THAN 6 hours
	$files 	= glob("./*.jpg");
  	$now   	= time();

	foreach ($files as $file) 
	{
		if (is_file($file)) 
		{
			if ($now - filemtime($file) >= 3600 * 6)
			{ 
				unlink($file);
			}
		}
  	}
	
	// 2. LIST THE .JPG FILES BY DECENDING TIME
	$files = glob("./*.jpg");
	usort($files, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));	// https://stackoverflow.com/questions/124958/glob-sort-by-date

	// 3. DISPLAY THE HTML PAGE
	$title = 'Photos received <span class="hover_red" style="font-size:smaller; margin-left:2em;border:1px solid black; padding:10px" onClick="window.location.href=window.location.href">Refresh</span>' ;  // (modified by CHR)
	echo "<!DOCTYPE html>";
	echo "<html style='background:#dadada'>";
	echo "<head>";
	echo "	<title>".$title."</title>";
	echo "	<meta charset='utf-8'>";
	echo "	<meta name='viewport' content='width=device-width,height=device-height,initial-scale=1.0,maximum-scale=1.0, viewport-fit=cover'>";
	echo "	<style>";
	echo "		body { background: #dadada; font-family: 'Lucida Sans', Helvetica, Arial, 'Lucida Grande', sans-serif; font-weight: 400; font-size: 14px; line-height: 18px; padding: 0; margin: 0; text-align: center;}";
	echo "		@media only screen and (max-width: 700px) { .wrap { padding: 15px; } }";
	echo "		h1 { text-align: center; margin: 40px 0; font-size: 22px; font-weight: bold; color: #666; }";
	echo "		.hover_red:hover{background:#948282; color:white}";
	echo "	</style>";
	echo "</head>";
	echo "<body>";
	echo "<br><br><b>POPPY SHOW-ME</b> DEMO ONLY - NOT FOR REAL USE<br><br><hr><br><b>Send the <a class='hover_red' href='" . BASE_URL . "?key=" . $key . "'>link</a> to the caller</b>:<br>";
	echo "<form action='send_sms.php' method='post'>";
	echo "	<p><input type='hidden' name='key' value='".$key."' /></p>";
	echo "	GSM (eg : 32123456789): <input type='number' name='to' /> &nbsp; <input class='hover_red' type='submit' style='border:1px solid black; padding:3px 9px; font-weight:bold; font-family:Lucida Sans' value='OK'>";
	echo "</form><br><hr>";
	echo "<h1>".$title."</h1>";

	// 4. DISPLAY THE IMAGES
	foreach ($files as $file) 
	{
		if (is_file($file)) 
		{
			echo "<img style='width:600px' src='".$file."'/><br><br>";
		}
  	}
	echo "<center>christophe@my-poppy.eu - github.com/ccloquet/showme</center>";
	echo "</body>";
	echo "</html>";

?>
