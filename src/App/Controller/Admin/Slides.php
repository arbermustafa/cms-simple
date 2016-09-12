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
use \App\Service\Slide;

class Slides extends Base
{
    public static function index($page)
    {
        $results = Slide::getList((int) $page);

        self::response('Admin/Slides/list.html', array(
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
            'slide'   => '',
            'message' => null
        );

        if ($request->isPost()) {
            $slide = Slide::add($request->post());

            if (isset($slide['id']) && (int) $slide['id'] !== 0) {
                $session->offsetSet('message', $slide['message']);

                return $app->redirectTo('intranet.slides.edit', array('id' => $slide['id']));
            }

            $result['message'] = $slide['message'];
            $result['slide'] = $request->post();
        }

        self::response('Admin/Slides/add.html', $result);
    }

    public static function edit($id)
    {
        $app = self::_getApp();
        $request = $app->request();
        $session = $app->session;
        $result = array(
            'slide'   => Slide::getSlide((int) $id),
            'message' => $session->offsetGet('message')
        );

        if ($request->isPost()) {
            $slide = Slide::edit($request->post());

            $result['message'] = $slide['message'];
            $result['slide'] = Slide::getSlide((int) $id);
        }

        self::response('Admin/Slides/edit.html', $result);
    }

    public static function delete($id)
    {
        $app = self::_getApp();
        $session = $app->session;
        $result = Slide::delete((int) $id);

        $session->offsetSet('message', $result);

        return $app->redirectTo('intranet.slides.list', array('page' => 1));
    }
}
