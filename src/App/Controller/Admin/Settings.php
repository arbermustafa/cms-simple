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

class Settings extends Base
{
    public static function index()
    {
        self::response('Admin/Settings/index.html', array());
    }
}
