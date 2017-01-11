<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ActionButtonAttributeTrait;
class Article extends Model
{
	use ActionButtonAttributeTrait;

    protected $table = 'articles';

    protected $fillable = ['category_id','title','intro','img','content_html','content_mark','status'];

    private $action = 'article';

    public function tag()
    {
    	return $this->belongsToMany('App\Models\Tag','article_tag','article_id','tag_id')->withTimestamps();
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }
}
