<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

use \App\Service\Content as ContentService;

class Content extends Base
{
    public static function archive($slug)
    {
        self::response('Content/archive.html', array());
    }

    public static function post($slug)
    {
        $content = ContentService::getContentBySlug($slug);
        self::response('Content/post.html', array($content));
    }
}
