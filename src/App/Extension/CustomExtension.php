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
use \App\Service\Content;
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
            new \Twig_SimpleFunction('serverUrl', array($this, 'serverUrl')),
            new \Twig_SimpleFunction('routeParams', array($this, 'routeParams')),
            new \Twig_SimpleFunction('queryStringParams', array($this, 'queryStringParams')),
            new \Twig_SimpleFunction('reqParams', array($this, 'reqParams')),
            new \Twig_SimpleFunction('getSetting', array($this, 'getSetting')),
            new \Twig_SimpleFunction('getMenu', array($this, 'getMenu')),
            new \Twig_SimpleFunction('renderNestableMenu', array($this, 'renderNestableMenu')),
            new \Twig_SimpleFunction('getFlashMessage', array($this, 'getFlashMessage')),
            new \Twig_SimpleFunction('getSitemapFor', array($this, 'getSitemapFor')),
            new \Twig_SimpleFunction('words', array($this, 'words')),
            new \Twig_SimpleFunction('isAllowed', array($this, 'isAllowed')),
            new \Twig_SimpleFunction('str_repeat', array($this, 'str_repeat')),
            new \Twig_SimpleFunction('isExpiredAuthToken', array($this, 'isExpiredAuthToken')),
            new \Twig_SimpleFunction('doShortcode', array($this, 'doShortcode'))
        );
    }

    public function serverUrl()
    {
        $app = Slim::getInstance();
        $request = $app->request;

        return $request->getUrl();
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
        return Setting::getByKey($key);
    }

    public function getMenu($menu = 'main-menu')
    {
        $menuFromDB = Menu::getMenuByName($menu);

        if ('main-menu' === $menu) {
            $params = $this->routeParams();
            $currentUrl['slug'] = (!empty($params['slug'])) ? $params['slug'] : $_SERVER['REQUEST_URI'];

            return Helper::renderMenu($menuFromDB, $currentUrl['slug']);
        }

        return Helper::menuAsArray($menuFromDB);
    }

    public function renderNestableMenu($menu = array())
    {
        return Helper::renderNestableMenu($menu);
    }

    public function getFlashMessage($key = null)
    {
        $app = Slim::getInstance();
        $session = $app->session;

        $result = $session->offsetGet($key);
        $session->offsetUnset($key);

        return $result;
    }

    public function getSitemapFor($type = 'page')
    {
        $result = array();
        $sitemap = Content::getContentAsArray($type);

        if ($sitemap) {
            $result = $sitemap;
        }

        return $result;
    }

    public function words($str = '', $len = 100)
    {
        return Str::words($str, $len);
    }

    public function isAllowed($resource, $permission = null)
    {
        $app = Slim::getInstance();
        $auth = $app->auth;
        $acl = $app->acl;

        $identity = $auth->getIdentity();

        return $acl->isAllowed($identity['role'], $resource, $permission);
    }

    public function str_repeat($str = '', $multiplier = 0)
    {
        return str_repeat($str, $multiplier);
    }

    public function isExpiredAuthToken($time)
    {
        return time() < $time;
    }

    public function doShortcode($content)
    {
        $app = Slim::getInstance();
        $shortcode = $app->shortcode;

        return $shortcode->doShortcode(html_entity_decode($content));
    }
}
