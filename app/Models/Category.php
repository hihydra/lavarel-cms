<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ActionButtonAttributeTrait;

class Category extends Model
{
	use ActionButtonAttributeTrait;

    private $action = 'category';

    protected $fillable = ['name','pid','url','icon','description','sort','status'];

    public function setSortAttribute($value)
    {
    	if ($value && is_numeric($value)) {
	        $this->attributes['sort'] = intval($value);
    	}else{
    		$this->attributes['sort'] = 0;
    	}
    }

    public function setStatusAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['status'] = 1;
        }else{
            $this->attributes['status'] = 0;
        }
    }
}
