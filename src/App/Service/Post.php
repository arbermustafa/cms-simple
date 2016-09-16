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

class Post extends Content
{
    public static function getPost($id)
    {
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $tag = __CLASS__;

        $post = parent::getContent($id, $key, $tag);

        if ($post && $post['categories']) {
            foreach ($post['categories'] as $category) {
                $post['category'][] = $category['id'];
            }

            unset($post['categories']);
        }

        return $post;
    }

    public static function getPostList($page = 1, $itemPerPage = 10)
    {
        return parent::getList('post', __CLASS__, $page, $itemPerPage);
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
            $params['type'] = 'post';
            $params['slug'] = '';

            try {
                if ($hasUploads) {
                    Helper::resizeImage($params['featured_photo'], array(
                        'w' => 800
                    ));
                }

                $post = ContentModel::create($params);
                $post->categories()->sync($params['category']);

                $cache->clearByTags(array(__CLASS__));

                self::clearCache();

                if (isset($params['fb']) && (int) $params['fb'] === 1) {
                    $app = self::_getApp();
                    $request = $app->request;
                    Social::publishToFb(array(
                        'message' => $post->title,
                        'link'    => $request->getUrl() . '/' . $post->slug
                    ));
                }

                if (isset($params['in']) && (int) $params['in'] === 1) {
                    $app = self::_getApp();
                    $request = $app->request;

                    $post = array(
                        'title' => $post->title,
                        'description' => substr($post->content, 0, 256),
                        'submitted-url' => $request->getUrl() . '/' . $post->slug
                    );

                    if (isset($post->featured_photo)) {
                        $post['submitted-image-url'] = $request->getUrl() . '/uploads/' . $post->featured_photo;
                    }

                    Social::publishToIn($post);
                }

                return array('message' => array('success' => 'Post created'), 'id' => $post->id);
            } catch (\Exception $e) {
                $log->error($e);
                if ($hasUploads) {
                    Helper::deleteFile($params['featured_photo']);
                }

                return array('message' => array('error' => 'Post not created!'));
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

                $post = ContentModel::find((int) $params['id']);
                $post->fill($params);
                $post->categories()->sync($params['category']);
                $post->save();

                if($params['existing-image'] === 0 && isset($params['old-file'])) {
                    Helper::deleteFile($params['old-file']);
                }

                $cache->clearByTags(array(__CLASS__));

                self::clearCache();

                if (isset($params['fb']) && (int) $params['fb'] === 1) {
                    $app = self::_getApp();
                    $request = $app->request;
                    Social::publishToFb(array(
                        'message' => $post->title,
                        'link' => $request->getUrl() . '/' . $post->slug
                    ));
                }

                if (isset($params['in']) && (int) $params['in'] === 1) {
                    $app = self::_getApp();
                    $request = $app->request;

                    $post = array(
                        'title' => $post->title,
                        'description' => substr($post->content, 0, 256),
                        'submitted-url' => $request->getUrl() . '/' . $post->slug
                    );

                    if (isset($post->featured_photo)) {
                        $post['submitted-image-url'] = $request->getUrl() . '/uploads/' . $post->featured_photo;
                    }

                    Social::publishToIn($post);
                }

                return array('message' => array('success' => 'Post modified'));
            } catch (\Exception $e) {
                $log->error($e);

                return array('message' => array('error' => 'Post not modified!'));
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
            $post = ContentModel::findOrFail((int) $id);

            if ($post->featured_photo) {
                Helper::deleteFile($post->featured_photo);
            }

            $post->categories()->detach();

            $post->delete();

            $cache->clearByTags(array(__CLASS__));

            self::clearCache();

            return array('success' => 'Post deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Post not deleted!');
        }
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('title', 'category.*', 'status', 'date'))->message('{field} is required');
        $validator->rule('numeric', array('category.*'))->message('Select a post category');
        $validator->rule('in', 'status', array('PUBLISHED', 'DRAFT'), true);
        $validator->rule('dateFormat', 'date', 'd.m.Y')->message('{field} not in correct format');
        $validator->labels(array(
            'title'      => 'Title',
            'category.*' => 'Post Category',
            'status'     => 'Status',
            'date'       => 'Date'
        ));

        return $validator;
    }
}
