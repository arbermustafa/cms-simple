<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Service;

class Social extends Base
{
    public static function publishToFb(array $params)
    {
        $app = self::_getApp();
        $facebook = $app->facebook;
        $log = self::_getLog();

        try {
            $accessToken = Setting::getByKey('fb_app_token')->key_value;
            $pageId = Setting::getByKey('fb_page_id')->key_value;

            $response = $facebook->post('/'. $pageId .'/feed', $params, $accessToken);

            return $response->getGraphNode();
        } catch(\Exception $e) {
            $log->error($e);
        }
    }

    public static function publishToIn(array $params)
    {
        $app = self::_getApp();
        $linkedin = $app->linkedin;
        $log = self::_getLog();

        try {
            $accessToken = Setting::getByKey('in_app_token')->key_value;
            $pageId = Setting::getByKey('in_page_id')->key_value;

            $linkedin->setAccessToken($accessToken);

            $post = array(
                'content' => $params,
                'visibility' => array('code' => 'anyone')
            );

            $response = $linkedin->post('companies/'. $pageId .'/shares?format=json', $post);

            return $response;
        } catch(\Exception $e) {
            $log->error($e);
        }
    }
}
