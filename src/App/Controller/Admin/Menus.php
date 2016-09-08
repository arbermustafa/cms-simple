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
use \App\Service\Menu;
use \App\Service\Category;
use \App\Service\Page;
use \App\Utility\Helper;

class Menus extends Base
{
    public static function index()
    {
        $app = self::_getApp();
        $request = $app->request();
        $name = $request->params('name');
        $menus = array('header-menu', 'main-menu', 'footer-menu');

        if (!in_array($name, $menus, true)) {
            $name = 'main-menu';
        }

        $result = array(
            'name'       => $name,
            'categories' => Category::getCategories('PUBLISHED'),
            'pages'      => Page::getPages('PUBLISHED'),
            'menu'       => Menu::getMenuByName($name),
            'message'    => null
        );

        if ($request->isPost()) {
            $result['message'] = Menu::edit($request->post());
            $result['menu'] = Menu::getMenuByName($name);
        }

        self::response('Admin/Menus/index.html', $result);
    }
}
