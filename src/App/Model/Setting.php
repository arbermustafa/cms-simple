<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Model;

use \App\Eloquent\Model\Base;

class Setting extends Base
{
    protected $table = 'setting';
    protected $fillable = array('key_name', 'key_value');
    protected $guarded = array('id');
    public $timestamps = false;

    public function setKeyNameAttribute($value)
    {
        $this->attributes['key_name'] = trim(strtolower($value));
    }

    public function setKeyValueAttribute($value)
    {
        $this->attributes['key_value'] = strtolower($value);
    }
}
