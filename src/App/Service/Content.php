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

class Content extends Base
{
    public static function getContentBySlug($slug)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$slug;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::where('slug', $slug)
                ->first();

            if ($content) {
                $result = $content->toArray();

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function searchFront($s = '', $page = 1, $itemPerPage = 10)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.md5($s).'_'.$page.'_'.$itemPerPage;
        $result = array(
            'data'        => null,
            'total'       => 0,
            'lastPage'    => 0,
            'currentPage' => 0
        );

        if ($s !== '' && $s !== null) {
            if (false == ($result = $cache->getItem($key))) {
                $contents = ContentModel::select('id', 'title', 'content', 'slug', 'status', 'date')
                    ->whereIn('type', array('page', 'post'))
                    ->where('status', 'PUBLISHED')
                    ->where(function($query) use ($s)
                    {
                        $query->where('title', 'LIKE', '%'. $s .'%')
                            ->orWhere('content', 'LIKE', '%'. $s .'%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginateToArray($page, $itemPerPage);

                if ($contents) {
                    $result = $contents;

                    $cache->setItem($key, $result);
                    $cache->setTags($key, array(__CLASS__));
                }
            }
        }

        return $result;
    }

    public static function getContent($id, $cacheKey = null, $cacheTag = null)
    {
        $cache = self::_getCache();
        $key = ($cacheKey) ?: __CLASS__.'_'.__FUNCTION__.'_'.$id;
        $tag = ($cacheTag) ?: __CLASS__;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::with('categories')
                ->find((int) $id);

            if ($content) {
                $result = $content->toArray();

                $cache->setItem($key, $result);
                $cache->setTags($key, array($tag));
            }
        }

        return $result;
    }

    public static function getContentCountByType($type = 'page')
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$type;
        $result = 0;

        if (false == ($result = $cache->getItem($key))) {
            $content = ContentModel::where('type', $type)
                ->where('status', 'PUBLISHED')
                ->count();

            if ($content) {
                $result = $content;

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function getList($type, $cacheTag, $page, $itemPerPage)
    {
        $log = self::_getLog();
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$type.'_'.$page.'_'.$itemPerPage;
        $tag = ($cacheTag) ?: __CLASS__;
        $result = array();

        if (false == ($result = $cache->getItem($key))) {
            $contents = ContentModel::select('id', 'title', 'status', 'created_at')
                ->where('type', $type)
                ->orderBy('created_at', 'desc')
                ->paginateToArray($page, $itemPerPage);

            if ($contents) {
                $result = $contents;

                $cache->setItem($key, $result);
                $cache->setTags($key, array($tag));
            }
        }

        return $result;
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
                'minWidth'  => 800,
                'maxWidth'  => null,
                'minHeight' => null,
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
}
