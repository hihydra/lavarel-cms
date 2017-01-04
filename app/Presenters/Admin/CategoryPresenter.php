<?php
namespace App\Presenters\Admin;

class categoryPresenter
{
	public function categoryNestable($categorys)
	{
		if ($categorys) {
			$item = '';
			foreach ($categorys as $v) {
				$item.= $this->getNestableItem($v);
			}
			return $item;
		}
		return '暂无分类';
	}

	/**
	 * 返回分类HTML代码
	 * @author 晚黎
	 * @date   2016-11-04T11:05:21+0800
	 * @param  [type]                   $category [description]
	 * @return [type]                         [description]
	 */
	protected function getNestableItem($category)
	{
		if ($category['child']) {
			return $this->getHandleList($category['id'],$category['name'],$category['icon'],$category['child']);
		}
		$labelInfo = $category['pid'] == 0 ?  'label-info':'label-warning';
		return <<<Eof
				<li class="dd-item dd3-item" data-id="{$category['id']}">
                    <div class="dd-handle dd3-handle">Drag</div>
                    <div class="dd3-content"><span class="label {$labelInfo}"><i class="{$category['icon']}"></i></span> {$category['name']} {$this->getActionButtons($category['id'])}</div>
                </li>
Eof;
	}
	/**
	 * 判断是否有子集
	 * @author 晚黎
	 * @date   2016-11-04T11:05:28+0800
	 * @param  [type]                   $id    [description]
	 * @param  [type]                   $name  [description]
	 * @param  [type]                   $child [description]
	 * @return [type]                          [description]
	 */
	protected function getHandleList($id,$name,$icon,$child)
	{
		$handle = '';
		if ($child) {
			foreach ($child as $v) {
				$handle .= $this->getNestableItem($v);
			}
		}

		$html = <<<Eof
		<li class="dd-item dd3-item" data-id="{$id}">
            <div class="dd-handle dd3-handle">Drag</div>
            <div class="dd3-content">
            	<span class="label label-info"><i class="{$icon}"></i></span> {$name} {$this->getActionButtons($id)}
            </div>
            <ol class="dd-list">
                {$handle}
            </ol>
        </li>
Eof;
		return $html;
	}

	/**
	 * 分类按钮
	 * @author 晚黎
	 * @date   2016-11-04T11:05:38+0800
	 * @param  [type]                   $id   [description]
	 * @param  boolean                  $bool [description]
	 * @return [type]                         [description]
	 */
	protected function getActionButtons($id)
	{
		$action = '<div class="pull-right">';
		if (auth()->user()->can(config('admin.permissions.category.show'))) {
			$action .= '<a href="javascript:;" class="btn btn-xs tooltips showInfo" data-href="'.url('admin/category',[$id]).'" data-toggle="tooltip" data-original-title="'.trans('admin/action.actionButton.show').'"  data-placement="top"><i class="fa fa-eye"></i></a>';
		}
		if (auth()->user()->can(config('admin.permissions.category.edit'))) {
			$action .= '<a href="javascript:;" data-href="'.url('admin/category/'.$id.'/edit').'" class="btn btn-xs tooltips editcategory" data-toggle="tooltip"data-original-title="'.trans('admin/action.actionButton.edit').'"  data-placement="top"><i class="fa fa-edit"></i></a>';
		}
		if (auth()->user()->can(config('admin.permissions.category.destroy'))) {
			$action .= '<a href="javascript:;" class="btn btn-xs tooltips destroy_item" data-id="'.$id.'" data-original-title="'.trans('admin/action.actionButton.destroy').'"data-toggle="tooltip"  data-placement="top"><i class="fa fa-trash"></i><form action="'.url('admin/category',[$id]).'" method="POST" style="display:none"><input type="hidden"name="_method" value="delete"><input type="hidden" name="_token" value="'.csrf_token().'"></form></a>';
		}
		$action .= '</div>';
		return $action;
	}
	/**
	 * 根据用户不同的权限显示不同的内容
	 * @author 晚黎
	 * @date   2016-11-04T13:40:11+0800
	 * @return [type]                   [description]
	 */
	public function canCreateCategory()
	{
		$canCreateCategory = auth()->user()->can(config('admin.permissions.category.create'));

		$title = $canCreateCategory ?  trans('admin/category.welcome'):trans('admin/category.sorry');
		$desc = $canCreateCategory ? trans('admin/category.description'):trans('admin/category.description_sorry');
		$createButton = $canCreateCategory ? '<br><a href="javascript:;" class="btn btn-primary m-t create_category">'.trans('admin/category.action.create').'</a>':'';
		return <<<Eof
		<div class="middle-box text-center animated fadeInRightBig">
            <h3 class="font-bold">{$title}</h3>
            <div class="error-desc">
                {$desc}{$createButton}
            </div>
        </div>
Eof;
	}
	/**
	 * 添加修改分类关系select
	 * @author 晚黎
	 * @date   2016-11-04T16:29:51+0800
	 * @param  [type]                   $categorys [description]
	 * @param  string                   $pid   [description]
	 * @return [type]                          [description]
	 */
	public function topCategoryList($categories,$pid = '')
	{
		$html = '<option value="0">'.trans('admin/category.topCategory').'</option>';
		if ($categories) {
			foreach ($categories as $v) {
				$html .= '<option value="'.$v['id'].'" '.$this->checkCategory($v['id'],$pid).'>'.$v['name'].'</option>';
			}
		}
		return $html;
	}

	public function checkcategory($categoryId,$pid)
	{
		if ($pid !== '') {
			if ($categoryId == $pid) {
				return 'selected="selected"';
			}
			return '';
		}
		return '';
	}
	/**
	 * 获取分类关系名称
	 * @author 晚黎
	 * @date   2016-11-04
	 * @param  [type]     $categorys [所有分类数据]
	 * @param  [type]     $pid   [分类关系pid]
	 * @return [type]            [description]
	 */
	public function topcategoryName($categorys,$pid)
	{
		if ($pid == 0) {
			return '顶级分类';
		}
		if ($categorys) {
			foreach ($categorys as $v) {
				if ($v['id'] == $pid) {
					return $v['name'];
				}
			}
		}
		return '';
	}
	/**
	 * 后台左侧分类
	 * @author 晚黎
	 * @date   2016-11-06
	 * @param  [type]     $sidebarcategorys [分类数据]
	 * @return [type]                   [HTML]
	 */
	public function sidebarcategoryList($sidebarcategorys)
	{
		$html = '';
		if ($sidebarcategorys) {
			foreach ($sidebarcategorys as $category) {
				if (!auth()->user()->can($category['slug'])) {
					continue;
				}
				if ($category['child']) {
					$active = active_class(if_uri_pattern(explode(',',$category['active'])),'active');
					$url = url($category['url']);
					$html .= <<<Eof
					<li class="{$active}">
			          	<a href="{$url}"><i class="{$category['icon']}"></i> <span class="nav-label">{$category['name']}</span> <span class="fa arrow"></span></a>
			          	<ul class="nav nav-second-level collapse">
			              	{$this->childcategory($category['child'])}
			          	</ul>
			      	</li>
Eof;
				}else{
					$html .= '<li class="'.active_class(if_uri_pattern(explode(',',$category['active'])),'active').'"><a href="'.url($category['url']).'"><i class="'.$category['icon'].'"></i> <span class="nav-label">'.$category['name'].'</span></a></li>';
				}
			}
		}
		return $html;
	}
	/**
	 * 多级分类显示
	 * @author 晚黎
	 * @date   2016-11-06
	 * @param  [type]     $childcategory [子分类数据]
	 * @return [type]                [HTML]
	 */
	public function childcategory($childcategory)
	{
		$html = '';
		if ($childcategory) {
			foreach ($childcategory as $v) {
				$html .= '<li class="'.active_class(if_uri_pattern(explode(',',$v['active'])),'active').'"><a href="'.url($v['url']).'">'.$v['name'].'</a></li>';
			}
		}
		return $html;
	}
}