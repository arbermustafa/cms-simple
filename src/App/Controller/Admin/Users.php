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

class Users extends Base
{
    public static function index()
    {
        self::response('Admin/Users/list.html', array());
    }

    public static function add()
    {
        self::response('Admin/Users/add.html', array());
    }

    public static function edit()
    {
        self::response('Admin/Users/edit.html', array());
    }

    public static function delete()
    {
    }
}
