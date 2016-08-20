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
}
