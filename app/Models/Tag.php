<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ActionButtonAttributeTrait;
class Tag extends Model
{

	use ActionButtonAttributeTrait;

	private $action = 'tag';

	protected $fillable = ['name','slug','icon'];


    public function setNameAttribute($value){

        $this->attributes['name'] = $value;

        //if(!$this->exists){
            $this->attributes['slug'] = str_slug($value);
        //}
    }

    public function setIconAttribute($value){
        if(!$value){
            $this->attributes['icon'] = 'fa fa-tag';
        }
    }

	public function article()
	{
		return $this->belongsToMany('App\Models\Article','article_tag','tag_id','article_id')->withTimestamps();
	}
}