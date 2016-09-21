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
use \App\Service\Setting;
use \App\Service\Content;

class Index extends Base
{
    public static function index()
    {
        $app = self::_getApp();
        $frontpage = (int) Setting::getByKey('fp');
        $content = Content::getContent($frontpage);

        if (!$content) {
            return $app->notFound();
        }

        self::response('Index/index.html', $content);
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
