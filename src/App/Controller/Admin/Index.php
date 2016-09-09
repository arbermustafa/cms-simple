<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller\Admin;

use \App\Controller\Base;
use \App\Service\User;
use \App\Service\Category;
use \App\Service\Content;

class Index extends Base
{
    public static function dashboard()
    {
        $data = array(
            'users'      => User::getUserCount(),
            'pages'      => Content::getContentCountByType('page'),
            'categories' => Category::getCategoryCount(),
            'posts'      => Content::getContentCountByType('post')
        );
        
        self::response('Admin/Index/dashboard.html', $data);
    }
}
