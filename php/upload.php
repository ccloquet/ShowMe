<?php
    require('../shared/params.php');
    require('../shared/manage_keys.php');

    if (!isset($_GET['key'])) {
        returns_error();
    }
    if (!verify_key($_GET['key'])) {
        returns_error();
    }
    if (!isset($_FILES['upload_file'])) {
        returns_error();
    }

    $key = $_GET['key'];
    $userid = get_user_from_key($key);

    if ($_FILES["upload_file"]["size"] > 50000000) {
        returns_error();
    }

    $tmp_name = $_FILES['upload_file']['tmp_name'];

    if (exif_imagetype($tmp_name) != IMAGETYPE_JPEG) {
        returns_error();
    }

    if (extension_loaded('imagick')) {
        $img = new Imagick();
        $img->readImageFile($tmp_name);
        /* -- from here does not work
        $data = $img->identifyImage();
        echo json_encode($data);

        if (!$data || strpos(strtolower($data['format']), 'jpeg') === FALSE)
        {
            returns_error();
        }*/
    }

    $type = getimagesize($tmp_name);

    if (($type === false) || (!in_array($type[2], [IMAGETYPE_JPEG]))) {
        returns_error();
    }

    if (!file_exists(BASE_FOLDER . '/' . $userid)) {
        mkdir(BASE_FOLDER . '/' . $userid, 0777, true);
    }

    if (move_uploaded_file($tmp_name, BASE_FOLDER . '/' . $userid . '/' . $key . uniqid() . uniqid() . '.jpg')) {
        returns_ok();
    } else {
        returns_error();
    }

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
