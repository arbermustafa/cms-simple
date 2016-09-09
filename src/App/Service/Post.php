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
    public static function getList($page = 1, $itemPerPage = 10)
    {
        return parent::getList('post', __CLASS__, $page, $itemPerPage);
    }

    public static function add(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $hasUploads = Helper::hasUploads();
        $result['error'] = '';

        if ($hasUploads) {

            $fpUploaded = self::handleUpload();

            if (isset($fpUploaded['featured_photo'])) {
                $params['featured_photo'] = $fpUploaded['featured_photo'];
            } else {
                $result['error'] .= self::_printErrors($fpUploaded['error']);
            }

        }

        $validator = self::validator($params);

        if ($validator->validate()) {
            $params['type'] = 'post';
            $params['slug'] = '';

            try {
                if ($hasUploads) {
                    Helper::resizeImage($params['featured_photo'], array(
                        'w' => 800,
                        'h' => null
                    ));
                }

                ContentModel::create($params)->categories()->sync($params['category']);

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Post created');
            } catch (\Exception $e) {
                $log->error($e);
                if ($hasUploads) {
                    Helper::deleteFile($params['featured_photo']);
                }

                return array('error' => 'Post not created!');
            }
        } else {
            if (isset($params['featured_photo'])) {
                Helper::deleteFile($params['featured_photo']);
            }

            $result['error'] .= self::_printErrors($validator->errors());
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
            'title'         => 'Title',
            'category.*' => 'Post Category',
            'status'        => 'Status',
            'date'          => 'Date'
        ));

        return $validator;
    }
}
