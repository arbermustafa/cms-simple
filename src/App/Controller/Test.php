<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

use \App\Model\Content;
use \Illuminate\Database\Capsule\Manager as DB;


class Test extends Base
{
    public static function index()
    {
        $result = \App\Service\Content::getContentAsArray('category');

        echo '<pre>';
        print_r($result);
        echo '</pre>';

        echo uniqid('img-'.date('YmdHis').'-');

        //\App\Utility\Helper::cropImage('/img-20160905140417-57cd5f41de981.jpg', array('width' => 940, 'height' => 350));
    }
}
