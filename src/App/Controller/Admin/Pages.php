<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller\Admin;

use \App\Controller\Base;
use \App\Service\Page;

class Pages extends Base
{
    public static function index($page)
    {
        $results = Page::getPageList((int) $page);

        self::response('Admin/Pages/list.html', array(
            'results'     => $results['data'],
            'total'       => (int) $results['total'],
            'lastPage'    => (int) $results['lastPage'],
            'currentPage' => (int) $results['currentPage']
        ));
    }

    public static function add()
    {
        $app = self::_getApp();
        $request = $app->request();
        $session = $app->session;
        $result = array(
            'page'    => '',
            'message' => null
        );

        if ($request->isPost()) {
            $page = Page::add($request->post());

            if (isset($page['id']) && (int) $page['id'] !== 0) {
                $session->offsetSet('message', $page['message']);

                return $app->redirectTo('intranet.pages.edit', array('id' => $page['id']));
            }

            $result['message'] = $page['message'];
            $result['page'] = $request->post();
        }

        self::response('Admin/Pages/add.html', $result);
    }

    public static function edit($id)
    {
        $app = self::_getApp();
        $request = $app->request();
        $session = $app->session;
        $result = array(
            'page'    => Page::getPage((int) $id),
            'message' => $session->offsetGet('message')
        );

        if ($request->isPost()) {
            $page = Page::edit($request->post());

            $result['message'] = $page['message'];
            $result['page'] = Page::getPage((int) $id);
        }

        self::response('Admin/Pages/edit.html', $result);
    }

    public static function delete($id)
    {
        $app = self::_getApp();
        $session = $app->session;
        $result = Page::delete((int) $id);

        $session->offsetSet('message', $result);

        return $app->redirectTo('intranet.pages.list', array('page' => 1));
    }
}
