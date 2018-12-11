<?php
	
	function create_usage_file_if_needed($userid)					// not great, should be a database (linked to the credential database)
	{
		if (!is_dir(USAGE_FOLDER))						// what does it assumes about the parent dir? +x?
		{
			mkdir(USAGE_FOLDER, 0777, true);
		}

		if (sizeof(glob(USAGE_FOLDER . $userid . '_*.usage')) === 0) 		// file structure: $userid . '_' . random string . '.usage' => can be stored in a potentially web-exposed folder but no one can see it
		{
			$filename = USAGE_FOLDER . $userid  . '_' . bin2hex(random_bytes(64)) . '.usage';

			file_put_contents($filename, time() . ','.INITIAL_TOPUP.PHP_EOL);		
		}
	}

	function top_up($userid, $credits)
	{
		create_usage_file_if_needed($userid);

		$filenames   = glob(USAGE_FOLDER . $userid . '_*.usage');
		file_put_contents($filenames[0], time().',' . $credits.PHP_EOL, FILE_APPEND);
	}

	function get_usage($userid)
	{
		create_usage_file_if_needed($userid);

		$filenames   = glob(USAGE_FOLDER . $userid . '_*.usage');		

		$usage_array = array_map('str_getcsv', file($filenames[0]));
		
		$remaining   = 0;

		foreach($usage_array as $usage)
		{
			$remaining += $usage[1];
		}

		return $remaining;
	}

	function set_usage($userid, $credits)
	{
		create_usage_file_if_needed($userid);

		$filenames   = glob(USAGE_FOLDER . $userid . '_*.usage');
		file_put_contents($filenames[0], time().',' . $credits.PHP_EOL, FILE_APPEND);
	}

?>
