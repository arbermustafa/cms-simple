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
use \App\Service\Category;

class Categories extends Base
{
    public static function index()
    {
        $app = self::_getApp();
        $results['categories'] = Category::getCategories();

        print_r($results['categories']);

        self::response('Admin/Categories/list.html', $results);
    }

    public static function add()
    {
        $app = self::_getApp();
        $request = $app->request();
        $session = $app->session;
        $result = array(
            'category'    => '',
            'parent_list' => Category::getCategories(),
            'message'     => null
        );

        if ($request->isPost()) {
            $category = Category::add($request->post());

            if (isset($category['id']) && (int) $category['id'] !== 0) {
                $session->offsetSet('message', $category['message']);

                return $app->redirectTo('intranet.categories.edit', array('id' => $category['id']));
            }

            $result['message'] = $category['message'];
            $result['category'] = $request->post();
            $result['parent_list'] = Category::getCategories();
        }

        self::response('Admin/Categories/add.html', $result);
    }

    public static function edit($id)
    {
        $app = self::_getApp();
        $request = $app->request();
        $result = array(
            'category'    => Category::getCategory((int) $id),
            'parent_list' => Category::getCategories(),
            'message'     => Category::getFlashMessage('message')
        );

        if ($request->isPost()) {
            $category = Category::edit($request->post());

            $result['message'] = $category['message'];
            $result['category'] = Category::getCategory((int) $id);
            $result['parent_list'] = Category::getCategories();
        }

        self::response('Admin/Categories/edit.html', $result);
    }

    public static function delete($id)
    {
        $app = self::_getApp();
        $session = $app->session;
        $result = Category::delete((int) $id);

        $session->offsetSet('message', $result);

        return $app->redirectTo('intranet.categories.list');
    }
}
