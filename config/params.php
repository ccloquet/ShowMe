<?php

	define('BASE_URL', 		'https://****/index.html');				// URL OF THE CLIENT PAGE
	define('BASE_FOLDER', 	'../received/');					// FOLDER WHERE THE IMAGES ARE STORED
	define('ENV',           'dev');

	$params = [
		// userids (confidential)							// secret -- allow to authentify that the link sent by SMS comes from this server
		// '' =>	['secret'=> '', 'name'=> '', 'sms_apikey' => '']
	];

	require_once ('params.'.ENV.'.php');
