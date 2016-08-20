<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Eloquent;

use \Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use \Illuminate\Database\Query\Builder as QueryBuilder;

class Builder extends EloquentBuilder
{
	public function __construct(QueryBuilder $query)
	{
		$this->query = $query;
	}

	/**
	 * Get paginated data as array
	 *
	 * @param  int   $page
	 * @param  int   $itemPerPage
	 * @return array $result
	 */
	public function paginateToArray($page = null, $itemPerPage = null)
	{
		$page = $page ?: 1;
		$itemPerPage = $itemPerPage ?: $this->model->getPerPage();
        $total = $this->count();
        $last_page = (int) ceil($total/$itemPerPage);

		if ($page > $last_page) {
			$page = $last_page;
		} elseif ($page < 1) {
			$page = 1;
		}

        $from = $total ? (($page - 1) * $itemPerPage + 1) : 0;
		$to = min($total, $page * $itemPerPage);

        $data = $this->skip($from-1)->take($itemPerPage);

		$result = array(
			'total'       => $total,
            'per_page'    => $itemPerPage,
			'currentPage' => $page,
            'lastPage'    => $last_page,
			'from'        => $from,
            'to'          => $to,
            'data'        => $data->get()->toArray()
		);

        return $result;
	}

	/**
	 * Get paginated data as json
	 *
	 * @param  int   $page
	 * @param  int   $itemPerPage
	 * @return JSONObject
	 */
	public function paginateToJson($page = 1, $itemPerPage = null)
	{
		$result = $this->paginateToArray($page, $itemPerPage);
		return json_encode($result);
	}
}
