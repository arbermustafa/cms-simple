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
use \App\Service\Category;
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
            new \Twig_SimpleFunction('doShortcode', array($this, 'doShortcode')),
            new \Twig_SimpleFunction('htmlDecode', array($this, 'htmlDecode')),
            new \Twig_SimpleFunction('latestNews', array($this, 'latestNews')),
            new \Twig_SimpleFunction('getContent', array($this, 'getContent'))
        );
    }

    public function serverUrl()
    {
        $app = Slim::getInstance(getenv('APP_NAME'));
        $request = $app->request;

        return $request->getUrl();
    }

    public function routeParams()
    {
        $app = Slim::getInstance(getenv('APP_NAME'));

        return $app->router->getCurrentRoute()->getParams();
    }

    public function queryStringParams()
    {
        $app = Slim::getInstance(getenv('APP_NAME'));
        $env = $app->environment();
        $uri = (!empty($env['QUERY_STRING'])) ? $env['QUERY_STRING'] : '';

        return $uri;
    }

    public function reqParams($key = null)
    {
        $app = Slim::getInstance(getenv('APP_NAME'));

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
        $app = Slim::getInstance(getenv('APP_NAME'));
        $session = $app->session;
        $result = null;

        if (null !== $key && '' !== $key && isset($session[$key])) {
            $result = $session[$key];
            unset($session[$key]);
        }

        return $result;
    }

    public function getSitemapFor($type = 'page', $status = 'PUBLISHED')
    {
        $result = array();
        $sitemap = Content::getContentAsArray($type, $status);

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
        $app = Slim::getInstance(getenv('APP_NAME'));
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
        $app = Slim::getInstance(getenv('APP_NAME'));
        $shortcode = $app->shortcode;

        return $shortcode->doShortcode($content);
    }

    public function htmlDecode($content)
    {
        return html_entity_decode($content);
    }

    public function latestNews($id, $items)
    {
        $result = null;
        $posts = Category::getCategoryPosts($id, $items);

        if ($posts) {
            $result = $posts;
        }

        return $result;
    }

    public function getContent($id)
    {
        $result = null;
        $content = Content::getContent($id);

        if ($content) {
            $result = $content;
        }

        return $result;
    }
}
