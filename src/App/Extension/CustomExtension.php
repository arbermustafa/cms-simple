<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Extension;

use \Slim\Slim;
use \Illuminate\Support\Str;
use \App\Service\Setting;
use \App\Service\Menu;
use \App\Utility\Helper;

class CustomExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'custom_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('routeParams', array($this, 'routeParams')),
            new \Twig_SimpleFunction('queryStringParams', array($this, 'queryStringParams')),
            new \Twig_SimpleFunction('reqParams', array($this, 'reqParams')),
            new \Twig_SimpleFunction('getSetting', array($this, 'getSetting')),
            new \Twig_SimpleFunction('getMenu', array($this, 'getMenu')),
            new \Twig_SimpleFunction('words', array($this, 'words'))
        );
    }

    public function routeParams()
    {
        $app = Slim::getInstance();

        return $app->router->getCurrentRoute()->getParams();
    }

    public function queryStringParams()
    {
        $app = Slim::getInstance();
        $env = $app->environment();
        $uri = (!empty($env['QUERY_STRING'])) ? $env['QUERY_STRING'] : '';

        return $uri;
    }

    public function reqParams($key = null)
    {
        $app = Slim::getInstance();

        return $app->request->params($key);
    }

    public function getSetting($key = 'fb')
    {
        $result = '';
        $model = Setting::getByKey($key);

        if (null !== $model) {
            $result = $model->key_value;
        }

        return $result;
    }

    public function getMenu($menu = 'main-menu')
    {
        $menuFromDB = Menu::getMenuByName($menu);

        if ('main-menu' === $menu) {
            $params = $this->routeParams();
            $currentUrl['slug'] = (!empty($params['slug'])) ? $params['slug'] : '';

            $menu = Helper::renderMenu($menuFromDB, $currentUrl['slug']);
        }

        return $menu;
    }

    public function words($str = '', $len = 100)
    {
        return Str::words($str, $len);
    }
}
