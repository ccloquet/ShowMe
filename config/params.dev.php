<?php

	define('TWILIO_SID', '');
	define('TWILIO_APIKEY', '');

	define('BASE_URL', 		'https://****/html/showme.html');		// URL OF THE CLIENT PAGE

	define('BASE_FOLDER', 		'../received/');				// FOLDER WHERE THE IMAGES ARE STORED
	define('USAGE_FOLDER', 		'../db/');					// FOLDER WHERE THE USAGE DATA ARE STORED

	define ("INITIAL_TOPUP", 10);							// by default, gives 5 credits the first time


    $params = array_merge($params, [
        1 =>   ['secret'=> 'azerty', 'name'=> 'test user', 'sms_apikey' => '']
    ]);

    // a way to generate keys: 
    //for i in {1..20}; do head -n 8192 /dev/urandom | openssl sha256; sleep 1; done
