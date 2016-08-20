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
