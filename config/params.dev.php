<?php

	define('TWILIO_SID', 		'');						// Twilio : for TURN & STUN servers (always) & for SMS API (if you choose to use TWILIO below)
	define('TWILIO_APIKEY', 	'');
	define('TWILIO_FROM', 		'');						// might be on a customer basis, in the table below. Begins with a "+" and the country code

	define('SMS_API', 		'TWILIO'); 					// TWILIO, CLICKATELL

	define('BASE_URL', 		'https://****/html/showme.html');		// URL OF THE CLIENT PAGE

	define('BASE_FOLDER', 		'../received/');				// FOLDER WHERE THE IMAGES ARE STORED
	define('USAGE_FOLDER', 		'../db/');					// FOLDER WHERE THE USAGE DATA ARE STORED

	define ("INITIAL_TOPUP", 	10);						// by default, gives 10 credits the first time


    $params = array_merge($params, [
	// userids (confidential)							// secret -- allow to authentify that the link sent by SMS comes from this server
        1 =>   ['secret'=> 'azerty', 'name'=> 'test user', 'sms_apikey' => '']
    ]);

    // a way to generate keys: 
    //for i in {1..20}; do head -n 8192 /dev/urandom | openssl sha256; sleep 1; done
