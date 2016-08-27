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

class Category extends Content
{
    public static function getCategoryBySlug($slug)
    {
        $category = parent::getContentBySlug($slug);

        return $category;
    }

    public static function getCategory($id)
    {
        $category = parent::getContent($id);

        return $category;
    }

    public static function getCategoryCount()
    {
        $categoryCount = parent::getContentCountByType('category');

        return $categoryCount;
    }

    public static function getCategories()
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $categories = ContentModel::with('childrenRecursive')
                ->where('type', 'category')
                ->where('status', 'PUBLISHED')
                ->whereNull('parent')
                ->get();

            if ($categories) {
                $result = $categories->toArray();

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
            $params['type'] = 'category';

            try {
                ContentModel::create($params);

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Category created');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Category not created!');
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
                ContentModel::find((int) $params['id'])->fill($params)->save();

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Category modified');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Category not modified!');
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
            $deleted = ContentModel::findOrFail((int) $id)->delete();

            if ($deleted) {
                ContentModel::where('parent', (int) $id)->update(array('parent' => NULL));
            }

            $cache->clearByTags(array(__CLASS__));

            return array('success' => 'Category deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Category not deleted!');
        }
    }


    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('title'));
        $validator->rule('optional', 'parent');
        $validator->rule('integer', 'parent');

        return $validator;
    }
}
