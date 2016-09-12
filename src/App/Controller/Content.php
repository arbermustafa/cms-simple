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
        $app = self::_getApp();
        $content = ContentService::getContentBySlug($slug);

        if (!$content) {
            return $app->notFound();
        }

        self::response('Content/post.html', $content);
    }
}
