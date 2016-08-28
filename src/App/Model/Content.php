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
use \Illuminate\Database\Capsule\Manager as DB;
use \Illuminate\Support\Str;
use \App\Service\Base as BaseService;

class Content extends Base
{
    protected $table = 'content';
    protected $fillable = array('user_id', 'type', 'status', 'parent', 'date', 'title', 'content', 'slug');
    protected $guarded = array('id');

    public function user()
    {
        return $this->belongsTo('\App\Model\User');
    }

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = (int) $value;
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

    public function setStatusAttribute($value)
    {
        if (!in_array($value, array('PUBLISHED', 'DRAFT'), true)) {
            $value = 'DRAFT';
        }

        $this->attributes['status'] = $value;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = substr(strip_tags(trim($value)), 0, 255);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = strip_tags(trim($value), '<br><b><i><u><blockquote>');
    }

    public function setSlugAttribute($value)
    {
        $slug = Str::slug($this->title);

        if ($slug !== $this->getSlugAttribute($this->slug)) {
            $slugs = static::select("slug", DB::raw("SUBSTRING_INDEX(slug, '-', -1) AS slug_counter"))
                ->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'");

            if ($slugs->count() === 0) {
                $this->attributes['slug'] = $slug;
            } else {
                $lastSlugNumber = intval(str_replace($slug . '-', '', $slugs->orderBy(DB::raw('slug_counter * 1'), 'desc')->first()->slug));

                $this->attributes['slug'] = $slug . '-' . ($lastSlugNumber + 1);
            }
        }
    }

    public function getDateAttribute($value)
    {
        return date('d.m.Y H:i:s', strtotime($value));
    }

    public function getSlugAttribute($value)
    {
        return $value;
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d.m.Y H:i:s', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d.m.Y H:i:s', strtotime($value));
    }

    public function parent()
    {
       return $this->belongsTo('\App\Model\Content', 'parent');
    }

    public function children()
    {
        return $this->hasMany('\App\Model\Content', 'parent');
    }

    public function parentRecursive()
    {
       return $this->parent()->with('parentRecursive');
    }

    public function childrenRecursive()
    {
       return $this->children()->with('childrenRecursive');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function($content)
        {
            $identity = BaseService::getIdentity();

            return $content->setUserIdAttribute($identity['id']);
        });
    }
}
