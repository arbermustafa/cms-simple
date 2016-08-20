<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Utility;

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
}
