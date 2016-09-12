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
use \App\Service\Post;

class Posts extends Base
{
    public static function index($page)
    {
        $results = Post::getList((int) $page);

        self::response('Admin/Posts/list.html', array(
            'results'     => $results['data'],
            'total'       => (int) $results['total'],
            'lastPage'    => (int) $results['lastPage'],
            'currentPage' => (int) $results['currentPage']
        ));
    }

    public static function add()
    {
        $app = self::_getApp();
        $request = $app->request();
        $session = $app->session;
        $result = array(
            'post'       => '',
            'categories' => Category::getCategories('PUBLISHED'),
            'message'    => null
        );

        if ($request->isPost()) {
            $post = Post::add($request->post());

            if (isset($post['id']) && (int) $post['id'] !== 0) {
                $session->offsetSet('message', $post['message']);

                return $app->redirectTo('intranet.posts.edit', array('id' => $post['id']));
            }

            $result['message'] = $post['message'];
            $result['post'] = $request->post();
        }

        self::response('Admin/Posts/add.html', $result);
    }

    public static function edit($id)
    {
        $app = self::_getApp();
        $request = $app->request();
        $session = $app->session;
        $result = array(
            'post'       => Post::getPost((int) $id),
            'categories' => Category::getCategories('PUBLISHED'),
            'message'    => $session->offsetGet('message')
        );

        if ($request->isPost()) {
            $post = Post::edit($request->post());

            $result['message'] = $post['message'];
            $result['post'] = Post::getPost((int) $id);
        }

        self::response('Admin/Posts/edit.html', $result);
    }

    public static function delete($id)
    {
        $app = self::_getApp();
        $session = $app->session;
        $result = Post::delete((int) $id);

        $session->offsetSet('message', $result);

        return $app->redirectTo('intranet.posts.list', array('page' => 1));
    }
}
