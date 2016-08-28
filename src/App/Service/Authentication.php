<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Service;

use \App\Model\User;
use \Valitron\Validator;

class Authentication extends Base
{
    public static function auth(array $params)
    {
        $log = self::_getLog();
        $validator = self::validator($params);

        if ($validator->validate()) {
            try {
                $user = User::select('id', 'firstname', 'lastname', 'email', 'role', 'last_login', 'password')
                    ->where('email', $params['email'])
                    ->where('status', 'ACTIVE')
                    ->first();

                if ($user) {

                    if (md5($params['password']) === $user->password) {
                        $user->last_login = date('Y-m-d H:i:s');
                        $user->save();

                        $result = $user->toArray();
                        unset($result['password']);

                        return $result;
                    }
                }

                throw new \Exception('Invalid credentials!');
            } catch (\Exception $e) {
                $log->error($e);

                throw new \Exception('Something went wrong, try again!');
            }
        } else {
            throw new \Exception('Invalid credentials');
        }
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('email', 'password'));
        $validator->rule('email', 'email');
        $validator->rule('regex', 'password', '/((?=.*\d)(?=.*[A-Za-z]).{8,})/');

        return $validator;
    }
}
