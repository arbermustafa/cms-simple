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
            $slides = ContentModel::select('title', 'content', 'featured_photo')
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

    public static function getList($page = 1, $itemPerPage = 10)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$page.'_'.$itemPerPage;
        $result = array(
            'data'        => null,
            'total'       => 0,
            'lastPage'    => 0,
            'currentPage' => 0
        );

        if (false == ($result = $cache->getItem($key))) {
            $slides = ContentModel::select('id', 'title', 'featured_photo', 'status')
                ->where('type', 'slide')
                ->orderBy('id', 'asc')
                ->paginateToArray($page, $itemPerPage);

            if ($slides) {
                $result = $slides;

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
        $result['error'] = '';

        $slideUploaded = self::handleUpload();

        if (isset($slideUploaded['featured_photo'])) {
            $params['featured_photo'] = $slideUploaded['featured_photo'];
        } else {
            $result['error'] .= self::_printErrors($slideUploaded['error']);
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

                ContentModel::create($params);

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Slide created');
            } catch (\Exception $e) {
                $log->error($e);
                Helper::deleteFile($params['featured_photo']);

                return array('error' => 'Slide not created!');
            }
        } else {
            if (isset($params['featured_photo'])) {
                Helper::deleteFile($params['featured_photo']);
            }

            $result['error'] .= self::_printErrors($validator->errors());
        }

        return $result;
    }

    public static function edit(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $result['error'] = '';

        $params['old-file'] = (isset($params['old-file'])) ? $params['old-file'] : null;
        $params['existing-image'] = (isset($params['existing-image'])) ? (int) $params['existing-image'] : 0;

        if ($params['existing-image'] === 0) {
            $slideUploaded = self::handleUpload();

            if (isset($slideUploaded['featured_photo'])) {
                $params['featured_photo'] = $slideUploaded['featured_photo'];
            } else {
                $result['error'] .= self::_printErrors($slideUploaded['error']);
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

                return array('success' => 'Slide modified');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Slide not modified!');
            }
        } else {
            if (isset($params['featured_photo']) && $params['existing-image'] === 0) {
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
            $slide = ContentModel::findOrFail((int) $id);

            Helper::deleteFile($slide->featured_photo);

            $slide->delete();

            $cache->clearByTags(array(__CLASS__));

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
        $validator->rule('optional', 'content');
        $validator->labels(array(
            'title'          => 'Title',
            'content'        => 'Content',
            'featured_photo' => 'Slide',
            'status'         => 'Status'
        ));

        return $validator;
    }
}
