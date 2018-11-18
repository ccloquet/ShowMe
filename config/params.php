<?php

	define('ENV',           'prod');

	$params = [
		// userids (confidential)							// secret -- allow to authentify that the link sent by SMS comes from this server
		// '' =>	['secret'=> '', 'name'=> '', 'sms_apikey' => '']
	];

	require_once ('params.'.ENV.'.php');
