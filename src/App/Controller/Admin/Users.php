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

class Users extends Base
{
    public static function index($page)
    {
        $app = self::_getApp();
        $results = User::getList((int) $page);

        self::response('Admin/Users/list.html', array(
            'results' => $results['data'],
            'total' => (int) $results['total'],
            'lastPage' => (int) $results['lastPage'],
            'currentPage' => (int) $results['currentPage'],
            'message' => $app->sessionContainer->message
        ));
    }

    public static function add()
    {
        $app = self::_getApp();
        $request = $app->request();
        $result = array(
            'user'    => '',
            'message' => null
        );

        if ($request->isPost()) {
            $user = User::add($request->post());

            $result['message'] = $user;
            $result['user'] = $request->post();
        }

        self::response('Admin/Users/add.html', $result);
    }

    public static function edit($id)
    {
        $app = self::_getApp();
        $request = $app->request();
        $result = array(
            'user'    => User::getUser((int) $id),
            'message' => null
        );

        if ($request->isPost()) {
            $user = User::edit($request->post());

            $result['message'] = $user;
            $result['user'] = $request->post();
        }

        self::response('Admin/Users/edit.html', $result);
    }

    public static function delete($id)
    {
        $app = self::_getApp();
        $session = $app->sessionContainer;
        $result = User::delete((int) $id);

        $session->offsetSet('message', $result);

        return $app->redirectTo('intranet.users.list', array('page' => 1));
    }
}
