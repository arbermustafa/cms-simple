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

class Category extends Content
{
    public static function getCategoryBySlug($slug)
    {
        $category = parent::getContentBySlug($slug);

        return $category;
    }

    public static function getCategory($id)
    {
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $tag = __CLASS__;

        $category = parent::getContent($id, $key, $tag);

        return $category;
    }

    public static function getCategoryPosts($category = null, $limit = 5, $status = 'PUBLISHED')
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$category.'_'.$limit.'_'.$status;
        $result = array();

        if ((int) $category !== 0 && $category !== null) {
            if (false == ($result = $cache->getItem($key))) {
                $posts = ContentModel::select('id', 'title', 'content', 'slug', 'featured_photo', 'date')
                    ->join('post_category', 'content.id', '=', 'post_category.content_id')
                    ->where('post_category.category_id', (int) $category)
                    ->where('type', 'post')
                    ->where('status', $status)
                    ->orderBy('date', 'desc')
                    ->take($limit)
                    ->get();

                if ($posts) {
                    $result = $posts->toArray();

                    $cache->setItem($key, $result);
                    $cache->setTags($key, array(__CLASS__));
                }
            }
        }

        return $result;
    }

    public static function getCategoryCount()
    {
        $categoryCount = parent::getContentCountByType('category');

        return $categoryCount;
    }

    public static function getCategories($status = null)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.md5($status);
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $categories = ContentModel::with('childrenRecursive')
                ->where('type', 'category');

            if (null !== $status && '' !== $status) {
                $categories = $categories->where('status', $status);
            }

            $categories = $categories->whereNull('parent')
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

        $params['parent'] = ($params['parent'] === '') ? null : (int) $params['parent'];

        $validator = self::validator($params);

        if ($validator->validate()) {
            $params['type'] = 'category';
            $params['slug'] = '';

            try {
                if ($hasUploads) {
                    Helper::resizeImage($params['featured_photo'], array(
                        'w' => 940
                    ));
                }

                $category = ContentModel::create($params);

                $cache->clearByTags(array(__CLASS__));
                self::clearCache();
                Menu::clearCache();

                return array('message' => array('success' => 'Category created'), 'id' => $category->id);
            } catch (\Exception $e) {
                $log->error($e);
                if ($hasUploads) {
                    Helper::deleteFile($params['featured_photo']);
                }

                return array('message' => array('error' => 'Category not created!'));
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

        $params['parent'] = ($params['parent'] === '' || $params['parent'] === null) ? null : (int) $params['parent'];

        $validator = self::validator($params);

        if ($validator->validate()) {
            $params['slug'] = '';

            try {
                if (isset($params['featured_photo']) && $params['existing-image'] === 0) {
                    Helper::resizeImage($params['featured_photo'], array(
                        'w' => 940
                    ));
                }

                $category = ContentModel::find((int) $params['id'])->fill($params)->save();

                if($params['existing-image'] === 0 && isset($params['old-file'])) {
                    Helper::deleteFile($params['old-file']);
                }

                $cache->clearByTags(array(__CLASS__));
                self::clearCache();
                Menu::clearCache();

                return array('message' => array('success' => 'Category modified'));
            } catch (\Exception $e) {
                $log->error($e);

                return array('message' => array('error' => 'Category not modified!'));
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
            $category = ContentModel::findOrFail((int) $id);

            if ($category->featured_photo) {
                Helper::deleteFile($category->featured_photo);
            }

            $deleted = $category->delete();

            if ($deleted) {
                ContentModel::where('parent', (int) $id)->update(array('parent' => NULL));
            }

            $cache->clearByTags(array(__CLASS__));
            self::clearCache();
            Menu::clearCache();

            return array('success' => 'Category deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Category not deleted!');
        }
    }

    public static function clearCache()
    {
        $cache = self::_getCache();

        $cache->clearByTags(array(__CLASS__));
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
