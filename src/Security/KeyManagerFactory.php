<?php
/**
 * Created by PhpStorm.
 * User: laurent
 * Date: 20/11/2018
 * Time: 17:49
 */

namespace Showme\Security;


class KeyManagerFactory
{

    /**
     * @return KeyManagerInterface
     */
    public static function create(){
        return new KeyManager();
    }

}