<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Utility;

use \Intervention\Image\ImageManagerStatic as Image;
use \App\Service\Menu;
use \App\Service\Setting;

class Helper
{
    public static function renderMenu($menu = array(), $currentUrl)
    {
        $frontpage = Setting::getByKey('fp');
        $html = '<ul>';

        if ($menu) {
            foreach ($menu as $item) {
                $class = '';
                if (isset($item['id']) && (int) $item['id'] !== 0) {
                    $itemFromDB = Menu::getMenuItem($item['id']);

                    if (!$itemFromDB) {
                        continue;
                    }

                    if ((int) $item['id'] === (int) $frontpage) {
                        $item['url'] = '/';
                        $item['title'] = $itemFromDB['title'];
                        $class = ($item['url'] === $currentUrl) ? 'active' : '';
                    } else {
                        $class = ($itemFromDB['slug'] === $currentUrl) ? 'active' : '';
                        $item['url'] = ($itemFromDB['type'] == 'category') ? '/archive/'. $itemFromDB['slug'] .'/1' : '/'. $itemFromDB['slug'];
                        $item['title'] = $itemFromDB['title'];
                    }
                } else {
                    $class = ($item['url'] === $currentUrl) ? 'active' : '';
                }

                $html .= '<li class="'. $class .'"><a href="'. $item['url'] .'" title="'. $item['title'] .'">'. $item['title'] .'</a>';

                if (!empty($item['children'])) {
                    $html .= self::renderMenu($item['children'], $currentUrl);
                }

                $html .= '</li>';
            }
        }

        $html .= '</ul>';

        return $html;
    }

    public static function menuAsArray(array $menu)
    {
        $frontpage = Setting::getByKey('fp');
        $menuArray = array();

        if ($menu) {
            foreach ($menu as $item) {
                if (isset($item['id']) && (int) $item['id'] !== 0) {
                    $itemFromDB = Menu::getMenuItem($item['id']);

                    if (!$itemFromDB) {
                        continue;
                    }

                    if ((int) $item['id'] === (int) $frontpage) {
                        $item['url'] = '/';
                        $item['title'] = $itemFromDB['title'];
                    } else {
                        $item['url'] = ($itemFromDB['type'] == 'category') ? '/archive/'. $itemFromDB['slug'] .'/1' : '/'. $itemFromDB['slug'];
                        $item['title'] = $itemFromDB['title'];
                    }
                }

                $menuArray[] = array('title' => $item['title'], 'url' => $item['url']);
            }
        }

        return $menuArray;
    }

    public static function renderNestableMenu($menu = array())
    {
        $html = '<ol class="dd-list">';

        if ($menu) {
            foreach ($menu as $item) {
                $data = '';

                if (isset($item['id']) && (int) $item['id'] !== 0) {
                    $itemFromDB = Menu::getMenuItem($item['id']);

                    if (!$itemFromDB) {
                        continue;
                    }

                    $item['title'] = $itemFromDB['title'];
                    $data .= 'data-id="'. $item['id'] .'"';
                } else {
                    $data .= 'data-url="'. $item['url'] .'" data-title="'. $item['title'] .'"';
                }

                $html .= '<li class="dd-item dd3-item" '. $data .'>';
                $html .= '<div class="dd-handle dd3-handle"><i class="fa fa-arrows-alt"></i></div>';
                $html .= '<div class="dd3-content">'. $item['title'] .'</div>';
                $html .= '<button type="button" class="dd3-delete btn btn-default btn-block" data-action="remove">';
                $html .= '<i class="fa fa-close"></i></button>';

                if (!empty($item['children'])) {
                    $html .= self::renderNestableMenu($item['children']);
                }

                $html .= '</li>';
            }
        }

        $html .= '</ol>';

        return $html;
    }

    public static function hasUploads($inputField = 'featured_photo')
    {
        if (isset($_FILES[$inputField]) && $_FILES[$inputField]['size'] > 0) {
            return true;
        }

        return false;
    }

    public static function cropImage($filename, array $options)
    {
        $w = (int) $options['w'];
        $h = (int) $options['h'];
        $x = (isset($options['x'])) ? (int) $options['x'] : 0;
        $y = (isset($options['y'])) ? (int) $options['y'] : 0;

        $image = Image::make(APP_UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename);

        $image->crop($w, $h, $x, $y)->save();
    }

    public static function resizeImage($filename, array $options)
    {
        $width = (isset($options['w'])) ? (int) $options['w'] : null;
        $height = (isset($options['h'])) ? (int) $options['h'] : null;

        $image = Image::make(APP_UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename);

        if ($width == null && $height != null) {
            $image->heighten($height)->save();
        } else if ($width != null && $height == null) {
            $image->widen($width)->save();
        } else {
            $image->resize($width, $height)->save();
        }
    }

    public static function deleteFile($filename)
    {
        if (file_exists(APP_UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename)) {
            unlink(APP_UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename);
        }
    }
}
