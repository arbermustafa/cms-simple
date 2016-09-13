<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

use \App\Service\Contact;

class Index extends Base
{
    public static function index()
    {
        self::response('Index/index.html', array());
    }

    public static function contactSubmit()
    {
        $app = self::_getApp();
        $request = $app->request();

        if ($request->isPost()) {
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(array('result' => Contact::send($request->post())));
        }
    }

    public static function sitemapGoogle()
    {
        self::response('Index/sitemap-google.html', array(), 'text/xml');
    }

    public static function rss()
    {
        self::response('Index/rss.html', array(), 'text/xml');
    }
}
