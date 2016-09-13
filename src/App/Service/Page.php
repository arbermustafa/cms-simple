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
use \App\Utility\Helper;

class Page extends Content
{
    public static function getPage($id)
    {
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $tag = __CLASS__;

        $page = parent::getContent($id, $key, $tag);

        return $page;
    }

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

    public static function add(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $hasUploads = Helper::hasUploads();
        $result['message']['error'] = '';

        if ($hasUploads) {
            $fpUploaded = self::handleUpload();

            if (isset($fpUploaded['featured_photo'])) {
                $params['featured_photo'] = $fpUploaded['featured_photo'];
            } else {
                $result['message']['error'] .= self::_printErrors($fpUploaded['error']);
            }
        }

        $validator = self::validator($params);

        if ($validator->validate()) {
            $params['type'] = 'page';
            $params['slug'] = '';

            try {
                if ($hasUploads) {
                    Helper::resizeImage($params['featured_photo'], array(
                        'w' => 800
                    ));
                }

                $page = ContentModel::create($params);

                $cache->clearByTags(array(__CLASS__));

                self::clearCache();

                return array('message' => array('success' => 'Page created'), 'id' => $page->id);
            } catch (\Exception $e) {
                $log->error($e);
                if ($hasUploads) {
                    Helper::deleteFile($params['featured_photo']);
                }

                return array('message' => array('error' => 'Page not created!'));
            }
        } else {
            if (isset($params['featured_photo'])) {
                Helper::deleteFile($params['featured_photo']);
            }

            $result['message']['error'] .= self::_printErrors($validator->errors());
        }

        return $result;
    }

    public static function edit(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $params = self::setNullParams($params);
        $hasUploads = Helper::hasUploads();
        $result['message']['error'] = '';

        $params['old-file'] = (isset($params['old-file'])) ? $params['old-file'] : null;
        $params['existing-image'] = (isset($params['existing-image'])) ? (int) $params['existing-image'] : 0;

        if ($params['existing-image'] === 0) {
            if ($hasUploads) {
                $fpUploaded = self::handleUpload();

                if (isset($fpUploaded['featured_photo'])) {
                    $params['featured_photo'] = $fpUploaded['featured_photo'];
                } else {
                    $result['message']['error'] .= self::_printErrors($fpUploaded['error']);
                }
            }
        } else {
            $params['featured_photo'] = $params['old-file'];
        }

        $validator = self::validator($params);

        if ($validator->validate()) {
            $params['slug'] = '';

            try {
                if (isset($params['featured_photo']) && $params['existing-image'] === 0) {
                    Helper::resizeImage($params['featured_photo'], array(
                        'w' => 800
                    ));
                }

                ContentModel::find((int) $params['id'])->fill($params)->save();

                if($params['existing-image'] === 0 && isset($params['old-file'])) {
                    Helper::deleteFile($params['old-file']);
                }

                $cache->clearByTags(array(__CLASS__));

                self::clearCache();

                return array('message' => array('success' => 'Page modified'));
            } catch (\Exception $e) {
                $log->error($e);

                return array('message' => array('error' => 'Page not modified!'));
            }
        } else {
            if (isset($params['featured_photo']) && $params['existing-image'] === 0) {
                Helper::deleteFile($params['featured_photo']);
            }

            $result['message']['error'] .= self::_printErrors($validator->errors());
        }

        return $result;
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

            self::clearCache();

            return array('success' => 'Page deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Page not deleted!');
        }
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('title', 'template', 'status'))->message('{field} is required');
        $validator->rule('in', 'status', array('PUBLISHED', 'DRAFT'), true);
        $validator->rule('in', 'template', array('default', 'contact', 'sitemap'), true);
        $validator->labels(array(
            'title'    => 'Title',
            'template' => 'Page template',
            'status'   => 'Status'
        ));

        return $validator;
    }
}
