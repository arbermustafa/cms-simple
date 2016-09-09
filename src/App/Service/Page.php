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

    public static function getList($page = 1, $itemPerPage = 10)
    {
        return parent::getList('page', __CLASS__, $page, $itemPerPage);
    }

    public static function delete($id)
    {
        $log = self::_getLog();
        $cache = self::_getCache();

        try {
            $page = ContentModel::findOrFail((int) $id);

            if ($page->featured_photo) {
                Helper::deleteFile($page->featured_photo);
            }

            $page->delete();

            $cache->clearByTags(array(__CLASS__));

            return array('success' => 'Page deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Page not deleted!');
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
