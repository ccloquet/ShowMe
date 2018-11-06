<?php

	// ------------------------------------------------------------------------------------	
	// purpose: key egneration & polling of the server for the images to display
	// only the names are transferred
	// then the client decides if it transfers the files
	// ------------------------------------------------------------------------------------

    	// security measures to avoid exploiting the user's browser :
    	// - only allowed users (through theit userid) will be able to retreive data from the server
    	// - only files with a verified key are shown 
    	// - only a white list of extensions is possible (currently (2018-11-01): jpg and mp4)
    	
	require_once('../includes.php');

	if (!isset($_POST['userid'])) 		returns_error(); 
	if (!validate_user($_POST['userid']))	returns_error();

	$userid = $_POST['userid'];

	switch($_POST['action'])
	{
		case 'init':
			// key generation when the client initializes
			$key    = create_key($userid);	
			 
			echo json_encode(['key'=>$key, 'base_url'=>BASE_URL]);
			break;

		case 'get_images':
			// polling for the images names

			// 1. DELETE FILES OLDER THAN 6 hours							// should be a cron
			$files 	= glob($userid . "/*.{jpg,mp4}", GLOB_BRACE);
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

			// 2. LIST THE .JPG FILES BY DESCENDING TIME
			$files 	= glob($userid . "/*.{jpg,mp4}", GLOB_BRACE);
			usort($files, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));	// https://stackoverflow.com/questions/124958/glob-sort-by-date

			// 3. RETURN THE IMAGES
			$arr = [];

			foreach ($files as $file) 
			{
				if (is_file($file)) 
				{
					$fname 		= str_replace($userid, '', $file);
					$key 		= substr($fname,1, strlen($fname)-31);			// remove extension (3 letters + dot) + random_bytes (26 alphanum)
				 
					if (!verify_key($key)) continue;

					$arr[] = ['src'=>$file, 'dt'=>filemtime($file)];
				}
		  	}
		
			echo json_encode($arr);
			break; 
		default:
			echo json_encode(['message'=>'error']);
	}

?>
