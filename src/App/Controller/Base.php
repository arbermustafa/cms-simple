<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

use \Slim\Slim;

class Base
{
    /**
     * Slim instance
     */
    protected static function _getApp()
    {
        return Slim::getInstance(getenv('APP_NAME'));
    }

    /**
     * Handle request params
     */
    protected static function getParams()
    {
        $request = self::_getApp()->request;

        if ($request->getMediaType() === 'application/json') {
            return self::buildArrayRequest($request->getBody());
        } else {
            return $request->params();
        }
    }

    /**
     * Build array request from body payload
     */
    protected static function buildArrayRequest($request)
    {
        if (!empty($request)) {
            $json = json_decode($request, true);

            if (!empty($json)) {
                return $json;
            }
        }

        return array();
    }

    /**
     * Render Response
     */
    protected static function response($template, $data, $contentType = null, $responseCode = 200)
    {
        if (null !== $contentType) {
            self::_getApp()->response->headers->set('Content-Type', $contentType);
        }

        self::_getApp()->render($template, $data, $responseCode);
    }
}
