<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Service;

use \Valitron\Validator;
use \ReCaptcha\ReCaptcha;

class Contact extends Base
{
    public static function send(array $params)
    {
        $log = self::_getLog();
        $validator = self::validator($params);
        $recaptcha = new \ReCaptcha\ReCaptcha(getenv('RECAPTCHA'));

        if ($validator->validate()) {
            $captcha = $recaptcha->verify($params['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

            if ($captcha->isSuccess()) {
                try {
                    $subject = 'Your Message to europa-re.com';
                    $key = Setting::getByKey('cfe');
                    $to = '';

                    if (null !== $key && '' !== $key) {
                        $to = $key;
                    }

                    $company = trim(strip_tags($params['company']));
                    $name = trim(strip_tags($params['name']));
                    $email = trim($params['email']);
        			$message = strip_tags($params['message']);

                    $headers = "From: $name <". $email .">\r\n";
                    $headers .= "X-Mailer: PHP/phpversion()";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $headers .= "Bcc: $to \r\n";

                    $html_message = '<html><body>';
                    $html_message .= 'Company: '. $company .'<br>';
                    $html_message .= 'Name: '. $name .'<br>';
                    $html_message .= 'Email: '. $email .'<br>';
                    $html_message .= 'Message: '. $message;
                    $html_message .= '</body></html>';

        			if (mail($email, $subject, $html_message, $headers)) {
                        return true;
                    }
                } catch (\Exception $e) {
                    $log->error($e);

                    return false;
                }
            }
        }

        return false;
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('name', 'email', 'message', 'g-recaptcha-response'));
        $validator->rule('email', 'email');

        return $validator;
    }
}
