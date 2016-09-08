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

class Menu extends Content
{
    public static function getMenuByName($name = 'main-menu')
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$name;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::select('content')
                ->where('type', 'menu')
                ->where('title', $name)
                ->first();

            if ($content) {
                $menu = $content->toArray();
                $result = json_decode($menu['content'], true);

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function getMenuItem($id)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $tag = __CLASS__;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::find((int) $id);

            $content = ContentModel::select('type', 'title', 'slug')
                ->where('id', (int) $id)
                ->first();

            if ($content) {
                $result = $content->toArray();

                $cache->setItem($key, $result);
                $cache->setTags($key, array($tag));
            }
        }

        return $result;
    }

    public static function edit(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $validator = self::validator($params);

        if ($validator->validate()) {
            try {
                ContentModel::updateOrCreate(array('type' => 'menu', 'title' => $params['title']), $params)->toArray();

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Menu modified');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Menu not modified!');
            }
        } else {
            return array('error' => self::_printErrors($validator->errors()));
        }
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('title', 'content'));
        $validator->rule('in', 'title', array('header-menu', 'main-menu', 'footer-menu'), true);

        return $validator;
    }
}
