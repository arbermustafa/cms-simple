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

class Helper
{
    public static function renderMenu($menu = array(), $currentUrl)
    {
        $html = '<ul>';
        foreach ($menu as $item) {
            $class = ($item['url'] === $currentUrl) ? 'active' : '';
            $html .= '<li class="'. $class .'"><a href="'. $item['url'] .'" title="'. $item['title'] .'">'. $item['title'] .'</a>';
            if (!empty($item['children'])) {
                $html .= self::renderMenu($item['children'], $currentUrl);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
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
        $width = $options['width'];
        $height = $options['height'];

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
