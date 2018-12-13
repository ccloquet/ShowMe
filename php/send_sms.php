<?php

	// ------------------------------------------------------------------
	// purpose: send an SMS containing a legitimate link to a citizen
	// so that he/she can send an image/video to the dispatcher
	// ------------------------------------------------------------------	

	require_once('../includes.php');

	// destination number check
	// should be in international format, without leading '+' or '00'
	if (!isset($_POST['to']))  					returns_error();
	if (!ctype_digit($_POST['to']))					returns_error();

	// checks that the userid is legitimate
	if (!isset($_POST['userid'])) 					returns_error();
	if (!validate_user($_POST['userid']))				returns_error();

	// check that the key is legitimate 
	// (ie, that the key comes from a legitimate user, within the correct timeframe)
	if (!isset($_POST['key'])) 					returns_error();
	if (!verify_key_and_user($_POST['key'], $_POST['userid'])) 	returns_error();

	$to   		= '+' . $_POST['to'];
	$key  		= $_POST['key'];	 
	$userid 	= $_POST['userid']; 
	$sms_apikey 	= $params[$userid]['sms_apikey'];

	$usage 		= get_usage($userid);

	if ($usage <= 0)
	{
		die(json_encode(['result'=>'NOCREDIT']));
	}

	// no other choice for the body => limits abuse
	$body 		= 'Open the link to take a picture. Ouvrez le lien pour prendre une photo. Open de link om een foto te nemen. ' . BASE_URL . '?key=' . $key;

	switch(SMS_API)
	{
		case 'TWILIO':
			$ret 		= send_twilio(TWILIO_SID, TWILIO_APIKEY, TWILIO_FROM, 	$body, $to);			
			break;
		default:
			$ret 		= send_clickatell($sms_apikey, 				$body, $to);
	}
			

	if ($ret !== false)
	{
		set_usage($userid, -1);
		$usage = get_usage($userid);					// query again because several people may be busy with the same account
		die(json_encode(['result'=>'OK', 'credits'=>$usage]));
	}
	else
	{
		$usage = get_usage($userid);					// query again because several people may be busy with the same account
		die(json_encode(['result'=>'ERROR', 'credits'=>$usage]));
	}
	

	// should echo remaining credits ($usage - 1)

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

		if ($ret === false)
		{
			return false;
		}				
		$ret    		= json_decode($ret, true);

		if ($ret['messages'][0]['accepted'] !== true) 	
		{
			return false;
		}
	}

	function send_twilio($sid, $token, $from, $body, $to)
	{
		$fields 	=  ["Body"=> $body, "From"=>$from, "To"=>$to] ;
		$ch    	 	= curl_init();
		curl_setopt($ch, CURLOPT_URL, 			'https://api.twilio.com/2010-04-01/Accounts/'.$sid.'/Messages.json');
		curl_setopt($ch, CURLOPT_POST, 			TRUE);
		curl_setopt($ch, CURLOPT_USERPWD, 		$sid . ':' . $token);
		curl_setopt($ch, CURLOPT_HTTPAUTH, 		CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,  	1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  	false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  	0);
		curl_setopt($ch, CURLOPT_POSTFIELDS,     	http_build_query($fields));
		$ret 		= curl_exec($ch);
		curl_close($ch);

		$ret 		= json_decode($ret, true);

		if ( isset($ret['sid']) )
		{
			return true;
		}

		return false;

	}


?>
