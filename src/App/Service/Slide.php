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
use \Upload\Storage\FileSystem;
use \Upload\File;
use \Upload\Validation\Mimetype;
use \Upload\Validation\Size;
use \App\Validation\Upload\ImageSize;
use \Valitron\Validator;
use \App\Utility\Helper;

class Slide extends Content
{
    public static function getSlides()
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $slides = ContentModel::select('title', 'content', 'url', 'featured_photo')
                ->where('type', 'slide')
                ->where('status', 'PUBLISHED')
                ->get();

            if ($slides) {
                $result = $slides->toArray();

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function getSlide($id)
    {
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $tag = __CLASS__;

        $slide = parent::getContent($id, $key, $tag);

        return $slide;
    }

    public static function getSlideList($page = 1, $itemPerPage = 10)
    {
        return parent::getList('slide', __CLASS__, $page, $itemPerPage);
    }

    public static function add(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $result['message']['error'] = '';

        $slideUploaded = self::handleUpload();

        if (isset($slideUploaded['featured_photo'])) {
            $params['featured_photo'] = $slideUploaded['featured_photo'];
        } else {
            $result['message']['error'] .= self::_printErrors($slideUploaded['error']);
        }

        $validator = self::validator($params);

        if ($validator->validate()) {
            $params['type'] = 'slide';

            try {

                Helper::cropImage($params['featured_photo'], array(
                    'w' => $params['w'],
                    'h' => $params['h'],
                    'x' => $params['x'],
                    'y' => $params['y']
                ));

                $slide = ContentModel::create($params);

                $cache->clearByTags(array(__CLASS__));

                self::clearCache();

                return array('message' => array('success' => 'Slide created'), 'id' => $slide->id);
            } catch (\Exception $e) {
                $log->error($e);
                Helper::deleteFile($params['featured_photo']);

                return array('message' => array('error' => 'Slide not created!'));
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
        $result['message']['error'] = '';

        $params['old-file'] = (isset($params['old-file'])) ? $params['old-file'] : null;
        $params['existing-image'] = (isset($params['existing-image'])) ? (int) $params['existing-image'] : 0;

        if ($params['existing-image'] === 0) {
            $slideUploaded = self::handleUpload();

            if (isset($slideUploaded['featured_photo'])) {
                $params['featured_photo'] = $slideUploaded['featured_photo'];
            } else {
                $result['message']['error'] .= self::_printErrors($slideUploaded['error']);
            }
        } else {
            $params['featured_photo'] = $params['old-file'];
        }

        $validator = self::validator($params);

        if ($validator->validate()) {
            try {
                if ($params['existing-image'] === 0) {
                    Helper::cropImage($params['featured_photo'], array(
                        'w' => $params['w'],
                        'h' => $params['h'],
                        'x' => $params['x'],
                        'y' => $params['y']
                    ));
                }

                ContentModel::find((int) $params['id'])->fill($params)->save();

                if ($params['existing-image'] === 0) {
                    Helper::deleteFile($params['old-file']);
                }

                $cache->clearByTags(array(__CLASS__));

                self::clearCache();

                return array('message' => array('success' => 'Slide modified'));
            } catch (\Exception $e) {
                $log->error($e);

                return array('message' => array('error' => 'Slide not modified!'));
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
            $slide = ContentModel::findOrFail((int) $id);

            Helper::deleteFile($slide->featured_photo);

            $slide->delete();

            $cache->clearByTags(array(__CLASS__));

            self::clearCache();

            return array('success' => 'Slide deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Slide not deleted!');
        }
    }

    public static function handleUpload()
    {
        $storage = new FileSystem(APP_UPLOAD_PATH);
        $file = new File('featured_photo', $storage);
        $file->setName(uniqid('img-' . date('YmdHis') . '-'));

        $file->addValidations(array(
            new Mimetype(array('image/png', 'image/jpg', 'image/jpeg')),
            new Size('5M'),
            new ImageSize(array(
                'minWidth'  => 940,
                'maxWidth'  => null,
                'minHeight' => 350,
                'maxHeight' => null
            ))
        ));

        try {
            $file->upload();

            return array('featured_photo' => $file->getNameWithExtension());
        } catch (\Exception $e) {
            return array('error' => $file->getErrors());
        }
    }


    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('title', 'featured_photo', 'status'))->message('{field} is required');
        $validator->rule('in', 'status', array('PUBLISHED', 'DRAFT'), true);
        $validator->rule('optional', array('content', 'url'));
        $validator->labels(array(
            'title'          => 'Title',
            'content'        => 'Content',
            'url'            => 'Link',
            'featured_photo' => 'Slide',
            'status'         => 'Status'
        ));

        return $validator;
    }
}
