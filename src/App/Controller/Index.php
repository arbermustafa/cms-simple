<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

class Index extends Base
{
    public static function index()
    {
        self::response('Index/index.html', array());
    }

    public static function sitemap()
    {
        self::response('Index/sitemap.html', array());
    }

    public static function imprint()
    {
        self::response('Index/imprint.html', array());
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
