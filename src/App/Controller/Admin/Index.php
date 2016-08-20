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

class Index extends Base
{
    public static function dashboard()
    {
        self::response('Admin/Index/dashboard.html', array());
    }
}
