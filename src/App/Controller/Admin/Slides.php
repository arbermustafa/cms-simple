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
        $result = array(
            'slide'   => '',
            'message' => null
        );

        if ($request->isPost()) {
            $slide = Slide::add($request->post());

            $result['message'] = $slide;
            $result['slide'] = $request->post();
        }

        self::response('Admin/Slides/add.html', $result);
    }

    public static function edit($id)
    {
        $app = self::_getApp();
        $request = $app->request();
        $result = array(
            'slide'   => Slide::getSlide((int) $id),
            'message' => null
        );

        if ($request->isPost()) {
            $data = $request->post();
            $data['old-file'] = (isset($data['old-file'])) ? $data['old-file'] : null;
            $data['existing-image'] = (isset($data['existing-image'])) ? (int) $data['existing-image'] : 0;

            $slide = Slide::edit($data);

            $result['message'] = $slide;
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
