<?php
	require_once('../shared/params.php');	 
	require_once('../shared/manage_keys.php');		 

	if (!isset($_POST['to']))  		die('');
	if (!ctype_digit($_POST['to']))		die('');

	if (!isset($_POST['key'])) 		die('');
	if (!verify_key($_POST['key'])) 	die('');

	$to   = '+' . $_POST['to'];
	$key  = $_POST['key'];

	$body = 'Open the link to take a picture. Ouvrez le lien pour prendre une photo. Open de link om een foto te nemen. ' . BASE_URL . '?key=' . $key;

	echo $to;

	send_clickatell($body, $to); 
	header('Location: index.php');

	function send_clickatell($body, $to) // only returns the result of the first one
	{
		$payload 		= json_encode( ["content"=> $body,"to"=>[$to]] );
		$url_clickatell     	= 'https://platform.clickatell.com/messages';
		$headers 		= ["Content-Type: application/json","Accept: application/json", "Authorization: " . CLICKATELL_APIKEY];
		$ch      		= curl_init();

		curl_setopt($ch, CURLOPT_URL, 			$url_clickatell);
		curl_setopt($ch, CURLOPT_HTTPHEADER, 		$headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 	1);
		curl_setopt($ch, CURLOPT_POST, 			TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 		$payload);
	
		$ret 			= curl_exec($ch);
		curl_close($ch);

		echo $ret;
		//$ret     = json_decode($ret, true);
		//if ($ret['messages'][0]['accepted'] != true) 	return false;
	}

?>
