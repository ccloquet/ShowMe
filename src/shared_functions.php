<?php

     /**
     * @param int $httpErrorCode
     */
    function returns_error($httpErrorCode = 400)
    {
        die('ERROR');
    }

    function returns_ok()
    {
        die('OK');
    }

function get_mime_type($file) 
{
	// from: https://www.finalwebsites.com/php-mime-type-detection/
	$mtype = false;
	if (function_exists('finfo_open')) 
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mtype = finfo_file($finfo, $file);
		finfo_close($finfo);
	} 
	elseif (function_exists('mime_content_type')) 
	{
		$mtype = mime_content_type($file);
	} 
	return $mtype;
}

function log_attempt($logname, $result_txt)
{
	file_get_contents(EMAIL_WEBHOOK . '?value1='.$result_txt);	// to get notified very easily -- in production, should find smth more professional
	error_log(time() . ' # ' . date("Y-m-d H:i:s") . ' # ' . $_SERVER['REMOTE_ADDR'] . ' # ' . $_SERVER['REMOTE_HOST'] . ' # ' . $_SERVER['HTTP_USER_AGENT'] . ' # ' . $result_txt . PHP_EOL, 3, $logname);	 
}


?>
