<?php
/**
 * Europa Re
 * Rewritten from linkedinapi/linkedin
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Social\Facebook;

use \Facebook\PersistentData\PersistentDataInterface;

class CustomPersistentDataHandler implements PersistentDataInterface
{
    protected $sessionPrefix = 'FBRLH_';

    public function __construct(){}

    public function get($key)
    {
        if (isset($_SESSION[$this->sessionPrefix . $key])) {
            return $_SESSION[$this->sessionPrefix . $key];
        }

        return null;
    }

    public function set($key, $value)
    {
        $_SESSION[$this->sessionPrefix . $key] = $value;
    }
}
