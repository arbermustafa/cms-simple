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

class Page extends Content
{
    public static function getPages($status = null)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.md5($status);
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $pages = ContentModel::where('type', 'page');

            if (null !== $status && '' !== $status) {
                $pages = $pages->where('status', $status);
            }

            $pages = $pages->get();

            if ($pages) {
                $result = $pages->toArray();

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

        $params['parent'] = ($params['parent'] === '') ? null : (int) $params['parent'];

        if ($validator->validate()) {
            $params['type'] = 'category';
            $params['slug'] = '';

            try {
                ContentModel::create($params);

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Category created');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Category not created!');
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

        $params['parent'] = ($params['parent'] === '') ? null : (int) $params['parent'];

        if ($validator->validate()) {
            $params['slug'] = '';

            try {
                ContentModel::find((int) $params['id'])->fill($params)->save();

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Category modified');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Category not modified!');
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
        $validator->rule('required', array('title', 'status'))->message('{field} is required');
        $validator->rule('in', 'status', array('PUBLISHED', 'DRAFT'), true);
        $validator->rule('optional', 'parent');
        $validator->labels(array(
            'title'  => 'Title',
            'parent' => 'Parent Category',
            'status' => 'Status'
        ));

        return $validator;
    }
}
