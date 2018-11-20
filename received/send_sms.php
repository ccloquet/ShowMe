<?php

	// ------------------------------------------------------------------
	// purpose: send an SMS containing a legitimate link to a citizen
	// so that he/she can send an image/video to the dispatcher
	// ------------------------------------------------------------------	

	include_once('../includes.php');

	// destination number check
	// should be in international format, without leading '+' or '00'
	if (!isset($_POST['to']))  		returns_error();
	if (!ctype_digit($_POST['to']))		returns_error();

	// checks that the userid is legitimate
	if (!isset($_POST['userid'])) 		returns_error();
	if (!$keyManager->checkUser($_POST['userid']))	returns_error();

	// check that the key is legitimate 
	// (ie, that the key comes from a legitimate user, within the correct timeframe)	--should check it comes from the userid
	if (!isset($_POST['key'])) 		returns_error();
	if (!$keyManager->check($_POST['key'])) 	returns_error();


	$to   		= '+' . $_POST['to'];
	$key  		= $_POST['key'];	 
	$userid 	= $_POST['userid']; 
	$sms_apikey 	= $params[$userid]['sms_apikey'];

	// no other choice for the body => limits abuse
	$body 		= 'Open the link to take a picture. Ouvrez le lien pour prendre une photo. Open de link om een foto te nemen. ' . BASE_URL . '?key=' . $key;

	send_clickatell($sms_apikey, $body, $to); 

	function send_clickatell($sms_apikey, $body, $to) // only returns the result of the first one
	{
		// clickatell is quite easy to begin with
		$payload 		= json_encode( ["content"=> $body,"to"=>[$to]] );
		$url_clickatell     	= 'https://platform.clickatell.com/messages';
		$headers 		= ["Content-Type: application/json","Accept: application/json", "Authorization: " . $sms_apikey];
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
