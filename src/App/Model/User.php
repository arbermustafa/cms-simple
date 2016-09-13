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

class User extends Base
{
    protected $table = 'user';
    protected $fillable = array('firstname', 'lastname', 'email', 'password', 'role', 'status');
    protected $guarded = array('id', 'last_login');
    protected $hidden = array('password');
    public $timestamps = false;

    public function contents()
    {
        return $this->hasMany('\App\Model\Content');
    }

    public function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = ucfirst(strtolower($value));
    }

    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = ucfirst(strtolower($value));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = md5($value);
    }

    public function setStatusAttribute($value)
    {
        if (!in_array($value, array('ACTIVE', 'INACTIVE'), true)) {
            $value = 'INACTIVE';
        }

        $this->attributes['status'] = $value;
    }

    public function setRoleAttribute($value)
    {
        if (!in_array($value, array('ADMIN', 'AUTHOR'), true)) {
            $value = 'AUTHOR';
        }

        $this->attributes['role'] = $value;
    }

    public function getLastLoginAttribute($value)
    {
        if (null !== $value && '' !== $value)
            return date('d.m.Y H:i:s', strtotime($value));
    }
}
