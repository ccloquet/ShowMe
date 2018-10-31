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

?>
