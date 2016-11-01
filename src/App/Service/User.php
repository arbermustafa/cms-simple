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

    public static function getUserCount()
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__;
        $result = 0;

        if (false == ($result = $cache->getItem($key))) {
            $user = UserModel::where('status', 'ACTIVE')->count();

            if ($user) {
                $result = $user;

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
        $result = array(
            'data'        => null,
            'total'       => 0,
            'lastPage'    => 0,
            'currentPage' => 0
        );

        if (false == ($result = $cache->getItem($key))) {
            $users = UserModel::select('id', 'firstname', 'lastname', 'email', 'role', 'status', 'last_login')
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
        $validator = self::validator($params, true);

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
            return array('error' => self::_printErrors($validator->errors()));
        }
    }

    public static function edit(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $validator = self::validator($params);

        if (null === $params['password'] || '' === $params['password']) {
            unset($params['password']);
        }

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
            return array('error' => self::_printErrors($validator->errors()));
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

    public static function clearCache()
    {
        $cache = self::_getCache();

        $cache->clearByTags(array(__CLASS__));
    }

    private static function validator($params, $add = false)
    {
        $required = array('firstname', 'lastname', 'email', 'role', 'status');

        if ($add) {
            array_push($required, 'password');
        }

        $validator = new Validator($params);
        $validator->rule('required', $required)->message('{field} is required');
        $validator->rule('alpha', array('firstname', 'lastname'));
        $validator->rule('email', 'email');
        $validator->rule('regex', 'password', '/((?=.*\d)(?=.*[A-Za-z]).{8,})/');
        $validator->rule('in', 'role', array('ADMIN', 'AUTHOR'), true);
        $validator->rule('in', 'status', array('ACTIVE', 'INACTIVE'), true);
        $validator->labels(array(
            'firstname' => 'First Name',
            'lastname'  => 'Last Name',
            'email'     => 'Email',
            'role'      => 'Role',
            'status'    => 'Status'
        ));

        return $validator;
    }
}
