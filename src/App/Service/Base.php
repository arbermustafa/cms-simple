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
    /**
     * Slim instance
     */
    protected static function _getApp()
    {
        return Slim::getInstance();
    }

    /**
     * Log instance
     */
    public static function _getLog()
    {
        return self::_getApp()->log;
    }

    /**
     * Cache instance
     */
    public static function _getCache()
    {
        return self::_getApp()->cache;
    }

    /**
     * AUTH instance
     */
    public static function _getAuth()
    {
        return self::_getApp()->auth;
    }

    /**
     * Get current user identity
     * @return Zend\Authentication\Result
     */
    public static function getIdentity()
    {
        $auth = self::_getAuth();
        $identity = null;

        if($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
        }

        return $identity;
    }

    /**
     * Print Valitron error messages
     */
     public static function _printValitronErrors($errors = array())
     {
         $error = '';

         foreach ($errors as $message) {
             if (is_array($message)) {
                 $error .= implode($message, '<br>') . '<br>';
             } else {
                 $error .= $message;
             }
         }

         return $error;
     }
}
