<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Service;

use \Slim\Slim;

class Base
{
    protected static function _getApp()
    {
        return Slim::getInstance(getenv('APP_NAME'));
    }

    public static function _getLog()
    {
        return self::_getApp()->log;
    }

    public static function _getCache()
    {
        return self::_getApp()->cache;
    }

    public static function _getAuth()
    {
        return self::_getApp()->auth;
    }

    public static function getIdentity()
    {
        $auth = self::_getAuth();
        $identity = null;

        if($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
        }

        return $identity;
    }

    public static function getFlashMessage($key = null)
    {
        $session = self::_getApp()->session;
        $result = null;

        if (null !== $key && '' !== $key && isset($session[$key])) {
            $result = $session[$key];
            unset($session[$key]);
        }

        return $result;
    }

    public static function _printErrors($errors = array())
    {
        $error = '';

        foreach ($errors as $message) {
            if (is_array($message)) {
                $error .= implode($message, '<br>') . '<br>';
            } else {
                $error .= $message . '<br>';
            }
        }

        return $error;
    }

     public static function setNullParams(array $request)
     {
         foreach ($request as $key => $value) {
             if ($value == null || $value == '') {
                 $request[$key] = null;
             }
         }

         return $request;
     }
}
