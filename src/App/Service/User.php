<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Service;

use \App\Model\User as UserModel;
use \Valitron\Validator;

class User extends Base
{
    public static function getUser($id)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $user = UserModel::find((int) $id);

            if ($user) {
                $result = $user->toArray();

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function getList($page = 1, $itemPerPage = 10)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$page.'_'.$itemPerPage;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $users = UserModel::select('id', 'firstname', 'lastname', 'email', 'last_login')
                ->orderBy('firstname', 'asc')
                ->orderBy('lastname', 'asc')
                ->paginateToArray($page, $itemPerPage);

            if ($users) {
                $result = $users;

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function add(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $validator = self::validator($params);

        if ($validator->validate()) {
            try {
                UserModel::create($params);

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'User created');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'User not created!');
            }
        } else {
            return array('error' => $validator->errors());
        }
    }

    public static function edit(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $validator = self::validator($params);

        if ($validator->validate()) {
            try {
                UserModel::find((int) $params['id'])->fill($params)->save();

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'User modified');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'User not modified!');
            }
        } else {
            return array('error' => $validator->errors());
        }
    }

    public static function delete($id)
    {
        $log = self::_getLog();
        $cache = self::_getCache();

        try {
            UserModel::findOrFail((int) $id)->delete();

            $cache->clearByTags(array(__CLASS__));

            return array('success' => 'User deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'User not deleted!');
        }
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('firstname', 'lastname', 'email', 'role', 'status'));
        $validator->rule('alpha', array('firstname', 'lastname'));
        $validator->rule('email', 'email');
        $validator->rule('regex', 'password', '/((?=.*\d)(?=.*[A-Za-z]).{8,})/');
        $validator->rule('in', 'role', array('ADMIN', 'AUTHOR'), true);
        $validator->rule('in', 'status', array('ACTIVE', 'INACTIVE'), true);

        return $validator;
    }
}
