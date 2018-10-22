<?php

	require_once('params.php');
	// CREATE A KEY
	// WILL BE SMTH LIKE 20181019_1231_HASH_SIGN 

	function create_key()
	{
		$parts   = [];
	
		$parts[]  = date("YmdHi");
		$parts[]  = bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
		$parts[]  = my_hash(SECRET.$parts[0].$parts[1]);

		$key     = join('_', $parts);;
		
		return $key;
	}

	function verify_key($key)
	{
		$aValid = ['_']; 

		if (!ctype_alnum(str_replace($aValid, '', $key)) ) 	return false;
		if (strlen($key) > 512) 				return false;

		$parts = explode('_', $key);

		if (my_hash(SECRET.$parts[0].$parts[1]) != $parts[2]) 	return false;		// the key should have bbeen generated by the server

		$dt = date_create_from_format('YmdHi', $parts[0]);
		if (time() - $dt->getTimestamp() > 3600 * 6) 		return false;		// max 6 hours ago

		return true;
	}

	function my_hash($txt)
	{
		return hash('sha256', $txt);
	}

?>
