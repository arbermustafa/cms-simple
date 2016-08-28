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
        $slide = parent::getContent($id);

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
        $validator = self::validator($params);

        if ($validator->validate()) {
            $params['type'] = 'slide';

            try {
                ContentModel::create($params);

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Slide created');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Slide not created!');
            }
        } else {
            return array('error' => self::_printValitronErrors($validator->errors()));
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

                return array('success' => 'Slide modified');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Slide not modified!');
            }
        } else {
            return array('error' => self::_printValitronErrors($validator->errors()));
        }
    }

    public static function delete($id)
    {
        $log = self::_getLog();
        $cache = self::_getCache();

        try {
            ContentModel::findOrFail((int) $id)->delete();

            $cache->clearByTags(array(__CLASS__));

            return array('success' => 'Slide deleted');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Slide not deleted!');
        }
    }


    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('title', 'featured_photo'));

        return $validator;
    }
}
