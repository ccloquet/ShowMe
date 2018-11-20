<?php
	// ------------------------------------------------------------------------------------
	// purpose: key egneration & polling of the server for the images to display
	// only the names are transferred
	// then the client decides if it transfers the files
	// ------------------------------------------------------------------------------------

    	// security measures to avoid exploiting the user's browser :
    	// - only allowed users (through theit userid) will be able to retreive data from the server
    	// - only files with a verified key are shown 
    	// - only a white list of extensions is possible (currently (2018-11-01): jpg and mp4)
    	
	include_once '../includes.php';

	if (!isset($_POST['userid'])){
        returns_error();
	}

	if (!$keyManager->checkUser($_POST['userid'])){
		returns_error();
    }

	function init($keyManager){
        // key generation when the client initializes
        $key    = $keyManager->create($_POST['userid']);

        // get stun/turn servers from Twilio
        $fields =  ["TTl"=> '21600'];

        $ice_servers = [];
        if(!empty(TWILIO_SID)){
            $ch     = curl_init();
            curl_setopt($ch, CURLOPT_URL, 			'https://api.twilio.com/2010-04-01/Accounts/'.TWILIO_SID.'/Tokens.json');
            curl_setopt($ch, CURLOPT_POST, 			TRUE);
            curl_setopt($ch, CURLOPT_USERPWD, 		TWILIO_SID . ':' . TWILIO_APIKEY);
            curl_setopt($ch, CURLOPT_HTTPAUTH, 		CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,  	1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  	false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  	0);
            curl_setopt($ch, CURLOPT_POSTFIELDS,     	http_build_query($fields));
            $ret = curl_exec($ch);
            curl_close($ch);

            if ($ret != null)
            {
                $ret    	= json_decode($ret, true);
                $ice_servers 	= $ret["ice_servers"];
            }
        }

        return ['key'=>$key, 'ice_servers' => $ice_servers];
	}

	function verify_key($keyManager){
        $key    = $_POST['key'];
        $result = ['result'=>'NOK'];

        if ( $keyManager->check($key) )	// &  ($userid == get_user_from_key($key)
        {
            $result = ['result'=>'OK'];
        }

        return $result;
	}

	function get_images($keyManager){
        // polling for the images names

        // 1. DELETE FILES OLDER THAN 6 hours							// should be a cron
        $files 	= glob($_POST['userid'] . "/*.{jpg,mp4}", GLOB_BRACE);
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
        $files 	= glob($_POST['userid'] . "/*.{jpg,mp4}", GLOB_BRACE);
        usort($files, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));	// https://stackoverflow.com/questions/124958/glob-sort-by-date

        // 3. RETURN THE IMAGES
        $arr = [];

        foreach ($files as $file)
        {
            if (is_file($file))
            {
                $fname 		= str_replace($_POST['userid'], '', $file);
                $key 		= substr($fname,1, strlen($fname)-31);			// remove extension (3 letters + dot) + random_bytes (26 alphanum)

                if (!$keyManager->check($key)) continue;

                $arr[] = ['src'=>$file, 'dt'=>filemtime($file)];
            }
        }

        return $arr;
	}

	$result = call_user_func($_POST['action'], $keyManager);

	if($result === false){
		$result = ['message' => 'Error action not found'];
	}

	echo json_encode($result);
