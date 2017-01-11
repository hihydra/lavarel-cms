<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ActionButtonAttributeTrait;
class Tag extends Model
{

	use ActionButtonAttributeTrait;

	private $action = 'tag';

	protected $table = 'tags';

	protected $fillable = ['name','slug','icon'];

	public function article()
	{
		return $this->belongsToMany('App\Models\Article','article_tag','tag_id','article_id')->withTimestamps();
	}
}
