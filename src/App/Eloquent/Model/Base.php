<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Eloquent\Model;

use \Illuminate\Database\Eloquent\Model;
use \App\Eloquent\Builder;

class Base extends Model
{
	public function newEloquentBuilder($query)
	{
		$this->setPerPage(getenv('DB_ITEMPERPAGE_PAGINATION'));

		return new Builder($query);
	}

	public function updateOrCreate(array $attributes, array $values = array())
	{
    	$instance = static::firstOrNew($attributes);

    	$instance->fill($values)->save();

    	return $instance;
	}
}
