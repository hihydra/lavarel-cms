<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ActionButtonAttributeTrait;
use App\Traits\TextAttributeTrait;

class Article extends Model
{
	use ActionButtonAttributeTrait;
    use TextAttributeTrait;

    private $action = 'article';

    protected $fillable = ['title','category_id','intro','img','content_html','content_mark','status','user_id'];

    public function tag()
    {
    	return $this->belongsToMany('App\Models\Tag','article_tag','article_id','tag_id')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
