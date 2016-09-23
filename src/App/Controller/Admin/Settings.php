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
use \App\Service\Page;
use \App\Social\LinkedIn\LinkedIn;

class Settings extends Base
{
    public static function index()
    {
        $app = self::_getApp();
        $request = $app->request();
        $linkedin = $app->linkedin;
        //$facebook = $app->facebook;

        $auth_fb_url = ''; /*$facebook->getRedirectLoginHelper()->getLoginUrl(
            $request->getUrl() . $app->urlFor('social.fb'),
            array(
                'publish_pages',
                'manage_pages'
            )
        );*/

        $auth_in_url = $linkedin->getLoginUrl(array(
            LinkedIn::SCOPE_BASIC_PROFILE,
            LinkedIn::SCOPE_COMPANY_ADMIN
        ));

        $result = array(
            'pages'       => Page::getPages('PUBLISHED'),
            'result'      => null,
            'auth_fb_url' => $auth_fb_url,
            'auth_in_url' => $auth_in_url
        );

        if ($request->isPost()) {
            $result['result'] = Setting::edit($request->post());
        }

        self::response('Admin/Settings/index.html', $result);
    }
}
