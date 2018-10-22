<?php
	require('../shared/params.php');
	require('../shared/manage_keys.php');

	if (!isset($_GET['key'])) 					die('ERROR');
	if (!verify_key($_GET['key']))					die('ERROR');

	$key = $_GET['key'];
	
	if (isset($_FILES['upload_file'])) 
	{
		$tmp_name = $_FILES['upload_file']['tmp_name'];

		if (exif_imagetype($tmp_name) != IMAGETYPE_JPEG) die('ERROR');
	
		$type = getimagesize( $tmp_name );
		if ( ($type === false) ||  (!in_array( $type[2], [ IMAGETYPE_JPEG ] ) ) )
		{
			die('ERROR');
		}
	
		if(move_uploaded_file($tmp_name, BASE_FOLDER . $key . uniqid() . uniqid() . '.jpg'))
		{
			echo   " OK";
		} 
		else 
		{
			echo   " KO";
		}
		exit;
	} 
	else 
	{
		echo "ERROR";
	}
?>
