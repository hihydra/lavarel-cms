<?php
namespace App\Traits;
trait TextAttributeTrait
{
	/**
	 * 状态文字
	 * @author 晚黎
	 * @date   2016-11-03T15:16:00+0800
	 * @param  [type]                   $data [状态数据]
	 * @return [type]                             [Boolean]
	 */
	public function getStatusTextAttribute($data)
	{
		return trans('admin/text.status.'.$data);
	}

}