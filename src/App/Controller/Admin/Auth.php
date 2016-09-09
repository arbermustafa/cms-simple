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
use \App\Authentication\Adapter;
use \App\Service\User;

class Auth extends Base
{
    public static function login()
    {
        $app = self::_getApp();
        $request = $app->request();
        $result = array(
            'email'  => '',
            'errors' => ''
        );

        if ($request->isPost()) {
            $adapter = new Adapter();
            $adapter->setCredentials($request->post());
            $auth = $app->auth;
            $user = $auth->authenticate($adapter);

            if ($user->isValid()) {
                return $app->redirectTo('intranet.dashboard');
            } else {
                $result['errors'] = $user->getMessages();
                $result['email'] = $request->post('email');
            }
        }

        self::response('Admin/Auth/login.html', $result);
    }

    public static function profile()
    {
        $app = self::_getApp();
        $identity = $app->auth->getIdentity();
        $request = $app->request();
        $result = array(
            'user'    => User::getUser((int) $identity['id']),
            'message' => null
        );

        if ($request->isPost()) {
            $user = User::edit(array_merge($result['user'], $request->post()));

            $result['message'] = $user;
            $result['user'] = $request->post();
        }

        self::response('Admin/Auth/profile.html', $result);
    }

    public static function logout()
    {
        $app = self::_getApp();
        $auth = $app->auth;

        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
        }

        return $app->redirectTo('intranet.login');
    }
}
