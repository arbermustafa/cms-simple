<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Controller;

use \App\Service\Content as ContentService;

class Search extends Base
{
    public static function index($page)
    {
        $params = self::getParams();
        $results = ContentService::searchFront($params['s'], (int) $page);

        self::response('Search/index.html', array(
            'results'     => $results['data'],
            'total'       => (int) $results['total'],
            'lastPage'    => (int) $results['lastPage'],
            'currentPage' => (int) $results['currentPage']
        ));
    }
}
