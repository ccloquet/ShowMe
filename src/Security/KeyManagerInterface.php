<?php
/**
 * Created by PhpStorm.
 * User: laurent
 * Date: 20/11/2018
 * Time: 17:50
 */

namespace Showme\Security;

interface KeyManagerInterface
{
    /**
     * @param string $userid
     *
     * @return string
     */
    public function create($userid);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function check($key);

    /**
     * @param string $key
     *
     * @return string
     */
    public function getUser($key);

    /**
     * @param string $userid
     *
     * @return bool
     */
    public function checkUser($userid);
}