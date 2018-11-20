<?php

    require_once('vendor/autoload.php');
    require_once('config/params.php');
    require_once('src/shared_functions.php');

    use Showme\Security\KeyManagerFactory;

    $keyManager = KeyManagerFactory::create();
