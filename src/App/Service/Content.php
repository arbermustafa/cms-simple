<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Service;

use \App\Model\Content as ContentModel;
use \Valitron\Validator;

class Content extends Base
{
    public static function getContentBySlug($slug)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$slug;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::where('slug', $slug)
                ->first();

            if ($content) {
                $result = $content->toArray();

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function searchFront($s = '', $page = 1, $itemPerPage = 10)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.md5($s).'_'.$page.'_'.$itemPerPage;
        $result = array(
            'data'        => null,
            'total'       => 0,
            'lastPage'    => 0,
            'currentPage' => 0
        );

        if ($s !== '' && $s !== null) {
            if (false == ($result = $cache->getItem($key))) {
                $contents = ContentModel::select('id', 'title', 'content', 'slug', 'status', 'date')
                    ->whereIn('type', array('page', 'post'))
                    ->where('status', 'PUBLISHED')
                    ->where(function($query) use ($s)
                    {
                        $query->where('title', 'LIKE', '%'. $s .'%')
                            ->orWhere('content', 'LIKE', '%'. $s .'%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginateToArray($page, $itemPerPage);

                if ($contents) {
                    $result = $contents;

                    $cache->setItem($key, $result);
                    $cache->setTags($key, array(__CLASS__));
                }
            }
        }

        return $result;
    }

    public static function getContent($id)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::find((int) $id);

            if ($content) {
                $result = $content->toArray();

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function getContentCountByType($type = 'page')
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$type;
        $result = 0;

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::where('type', $type)
                ->where('status', 'PUBLISHED')
                ->count();

            if ($content) {
                $result = $content;

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function getList($type = 'page', $page = 1, $itemPerPage = 10)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$type.'_'.$page.'_'.$itemPerPage;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $contents = ContentModel::select('id', 'title', 'status', 'created_at')
                ->where('type', $type)
                ->orderBy('created_at', 'desc')
                ->paginateToArray($page, $itemPerPage);

            if ($contents) {
                $result = $contents;

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
            ContentModel::findOrFail((int) $id)->delete();

            $cache->clearByTags(array(__CLASS__));

            return array('success' => 'Content deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Content not deleted!');
        }
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        /*$validator->rule('required', array('firstname', 'lastname', 'email', 'role', 'status'));
        $validator->rule('alpha', array('firstname', 'lastname'));
        $validator->rule('email', 'email');
        $validator->rule('regex', 'password', '/((?=.*\d)(?=.*[A-Za-z]).{8,})/');
        $validator->rule('in', 'role', array('ADMIN', 'AUTHOR'), true);
        $validator->rule('in', 'status', array('ACTIVE', 'INACTIVE'), true);*/

        return $validator;
    }
}
