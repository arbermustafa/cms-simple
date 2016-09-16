<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

use \App\Service\Setting;
use \App\Social\LinkedIn;

class Social extends Base
{
    public static function authorizeFb()
    {
        $app = self::_getApp();
        $log = $app->log;
        $facebook = $app->facebook;

        if (isset($_REQUEST['code'])) {
            $_SESSION['FBRLH_state'] = $_REQUEST['state'];
        }

        $helper = $facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(\Exception $e) {
            $log->error($e);
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        } else {
            $expiresAt = $accessToken->getExpiresAt();
        }

        if (!$accessToken->isLongLived()) {
            $oAuth2Client = $facebook->getOAuth2Client();
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                $expiresAt = $accessToken->getExpiresAt();
            } catch(\Exception $e) {
                $log->error($e);
            }
        }

        try {
            Setting::edit(array('fb_app_token' => (string) $accessToken));
            Setting::edit(array('fb_app_token_expires' => $expiresAt->getTimestamp()));
        } catch(\Exception $e) {
            $log->error($e);
        }

        return $app->redirectTo('intranet.settings');
    }

    public static function authorizeIn()
    {
        $app = self::_getApp();
        $log = $app->log;
        $linkedin = $app->linkedin;

        if (isset($_REQUEST['code'])) {
            try {
                $accessToken = $linkedin->getAccessToken($_REQUEST['code']);
                Setting::edit(array('in_app_token' => $accessToken));
                Setting::edit(array('in_app_token_expires' => time() + $linkedin->getAccessTokenExpiration()));

            } catch(\Exception $e) {
                $log->error($e);
            }
        }

        return $app->redirectTo('intranet.settings');
    }
}
