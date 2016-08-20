<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

use \App\Model\Content;
use \Illuminate\Database\Capsule\Manager as DB;

class Test extends Base
{
    public static function index()
    {
        $app = self::_getApp();
        $auth = $app->auth;
        $auth->getStorage()->write(array(
            'id' => 1,
            'firstname' => 'Arber',
            'lastname' => 'Mustafa',
            'email' => 'amustafa@medialb.net',
            'role' => 'ADMIN',
            'last_login' => '18.08.2016 10:11:12'
        ));
        //$result = \App\Service\Authentication::auth(array(
        //    'email' => 'amustafa@medialb.net',
        //    'password' => 'arber009'
        //));

        $result = $auth->getIdentity();

        echo '<pre>';
        echo var_dump($result);
        echo '</pre>';
    }
}
