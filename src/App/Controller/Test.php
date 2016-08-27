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
        $result = \App\Service\User::getUserCount();

        echo '<pre>';
        echo var_dump($result);
        echo '</pre>';
    }
}
