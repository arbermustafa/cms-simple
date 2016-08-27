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
use \App\Service\Setting;

class Settings extends Base
{
    public static function index()
    {
        $app = self::_getApp();
        $request = $app->request();
        $result = array('result' => null);

        if ($request->isPost()) {
            $result['result'] = Setting::edit($request->post());
        }

        self::response('Admin/Settings/index.html', $result);
    }
}
