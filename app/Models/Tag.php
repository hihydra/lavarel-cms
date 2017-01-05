<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ActionButtonAttributeTrait;
class Tag extends Model
{

	use ActionButtonAttributeTrait;

    protected $table = 'tags';

    protected $fillable = ['name','sulg','icon'];

    private $action;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->action = config('admin.global.tag.action');
    }

    public function article()
    {
        return $this->belongsToMany('App\Models\Article','article_tag','tag_id','article_id')->withTimestamps();
    }
}
