<?php

	require_once('../includes.php');

	if (!isset($_POST['userid'])) 		returns_error(); 
	if (!validate_user($_POST['userid']))	returns_error();

	$userid = $_POST['userid'];

	switch($_POST['action'])
	{
		case 'init':
			$key    = create_key($userid);	
			 
			echo json_encode(['key'=>$key, 'base_url'=>BASE_URL]);
			break;

		case 'get_images':
			// 1. DELETE FILES OLDER THAN 6 hours							// should be a cron
			$files 	= glob($userid . "/*.jpg");
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
			$files 	= glob($userid . "/*.jpg");
			usort($files, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));	// https://stackoverflow.com/questions/124958/glob-sort-by-date

			// 3. RETURN THE IMAGES
			$arr = [];

			foreach ($files as $file) 
			{
				if (is_file($file)) 
				{
					$fname 		= str_replace($userid, '', $file);
					$key 		= substr($fname,1, strlen($fname)-31);
				 
					if (!verify_key($key)) continue;

					$arr[] = ['src'=>$file, 'dt'=>filemtime($file)];
				}
		  	}
		
			echo json_encode($arr);
			break; 
		default:
			echo json_encode(['message'=>'error']);
	}

	function returns_error()
	{
		die('ERROR');
	}
	

?>
