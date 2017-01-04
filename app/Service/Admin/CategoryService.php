<?php
namespace App\Service\Admin;
use App\Repositories\Eloquent\CategoryRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception,DB;
/**
* 分类service
*/
class CategoryService extends BaseService
{
	private $category;

	public function __construct(categoryRepositoryEloquent $category)
	{
		$this->category = $category;
	}

	/**
	 * 递归分类数据
	 * @author 晚黎
	 * @date   2016-11-04T10:43:11+0800
	 * @param  [type]                   $categorys [数据库或缓存中查询出来的数据]
	 * @param  integer                  $pid   [分类关系id]
	 * @return [type]                          [description]
	 */
	public function sortCategory($categories,$pid=0)
	{
		$arr = [];
		if (empty($categories)) {
			return '';
		}
		foreach ($categories as $key => $v) {
			if ($v['pid'] == $pid) {
				$arr[$key] = $v;
				$arr[$key]['child'] = self::sortcategory($categories,$v['id']);
			}
		}
		return $arr;
	}
	/**
	 * 排序子分类并缓存
	 * @author 晚黎
	 * @date   2016-11-04T10:44:11+0800
	 * @return [type]                   [Array]
	 */
	public function sortCategorySetCache()
	{
		$categories = $this->category->allCategories();
		if ($categories) {
			$categoryList = $this->sortCategory($categories);
			foreach ($categoryList as $key => &$v) {
				if ($v['child']) {
					$sort = array_column($v['child'], 'sort');
					array_multisort($sort,SORT_DESC,$v['child']);
				}
			}
			// 缓存分类数据
			cache()->forever(config('admin.global.cache.categoryList'),$categoryList);
			return $categoryList;

		}
		cache()->forget(config('admin.global.cache.categoryList'));
		return '';
	}
	/**
	 * 获取分类数据
	 * @author 晚黎
	 * @date   2016-11-04T10:45:38+0800
	 * @return [type]                   [description]
	 */
	public function getCategoryList()
	{
		// 判断数据是否缓存
		if (cache()->has(config('admin.global.cache.categoryList'))) {
			return cache()->get(config('admin.global.cache.categoryList'));
		}
		return $this->sortcategorySetCache();
	}
	/**
	 * 添加分类
	 * @author 晚黎
	 * @date   2016-11-04T15:10:32+0800
	 * @param  [type]                   $attributes [表单数据]
	 * @return [type]                               [Boolean]
	 */
	public function storeCategory($attributes)
	{
		try {
			$result = $this->category->create($attributes);
			if ($result) {
				// 更新缓存
				$this->sortcategorySetCache();
			}
			return [
				'status' => $result,
				'message' => $result ? trans('admin/alert.category.create_success'):trans('admin/alert.category.create_error'),
			];
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
	/**
	 * 根据分类ID查找数据
	 * @author 晚黎
	 * @date   2016-11-04T16:25:59+0800
	 * @param  [type]                   $id [description]
	 * @return [type]                       [description]
	 */
	public function findCategoryById($id)
	{
		$category = $this->category->find($id);
		if ($category){
			return $category;
		}
		// TODO替换正查找不到数据错误页面
		abort(404);
	}
	/**
	 * 修改分类数据
	 * @author 晚黎
	 * @date   2016-11-04
	 * @param  [type]     $attributes [表单数据]
	 * @param  [type]     $id         [resource路由id]
	 * @return [type]                 [Array]
	 */
	public function updateCategory($attributes,$id)
	{
		// 防止用户恶意修改表单id，如果id不一致直接跳转500
		if ($attributes['id'] != $id) {
			return [
				'status' => false,
				'message' => trans('admin/errors.user_error'),
			];
		}
		try {
			$isUpdate = $this->category->update($attributes,$id);
			if ($isUpdate) {
				// 更新缓存
				$this->sortcategorySetCache();
			}
			return [
				'status' => $isUpdate,
				'message' => $isUpdate ? trans('admin/alert.category.edit_success'):trans('admin/alert.category.edit_error'),
			];
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}


	}
	/**
	 * 删除分类
	 * @author 晚黎
	 * @date   2016-11-04
	 * @param  [type]     $id [分类ID]
	 * @return [type]         [description]
	 */
	public function destroyCategory($id)
	{
		try {
			$isDestroy = $this->category->delete($id);
			if ($isDestroy) {
				// 更新缓存
				$this->sortcategorySetCache();
			}
			flash_info($isDestroy,trans('admin/alert.category.destroy_success'),trans('admin/alert.category.destroy_error'));
			return $isDestroy;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}

	public function orderable($nestableData)
	{
		try {
			$dataArray = json_decode($nestableData,true);
			$categorys = array_values($this->getcategoryList());
			$categoryCount = count($dataArray);
			$bool = false;
			DB::beginTransaction();
			foreach ($dataArray as $k => $v) {
				$sort = $categoryCount - $k;
				if (!isset($categorys[$k])) {
					$this->category->update(['sort' => $sort,'pid' => 0],$v['id']);
					$bool = true;
				}else{
					if (isset($categorys[$k]['id']) && $v['id'] != $categorys[$k]['id']) {
						$this->category->update(['sort' => $sort,'pid' => 0],$v['id']);
						$bool = true;
					}
				}
				if (isset($v['children']) && !empty($v['children'])) {
					$childCount = count($v['children']);
					foreach ($v['children'] as $key => $child) {
						$chidlSort = $childCount - $key;
						if (!isset($categorys[$k]['child'][$key])) {
							foreach ($v['children'] as $index => $val) {
								$reIndex = $childCount - $index;
								$this->category->update(['pid' => $v['id'],'sort' => $reIndex],$val['id']);
							}
							$bool = true;
						}else{
							if (isset($categorys[$k]['child'][$key]) && ($child['id'] != $categorys[$k]['child'][$key]['id'])) {
								$this->category->update(['pid' => $v['id'],'sort' => $chidlSort],$child['id']);
								$bool = true;
							}
						}
					}
				}
			}
			DB::commit();
			if ($bool) {
				// 更新缓存
				$this->sortcategorySetCache();
			}
			return [
				'status' => $bool,
				'message' => $bool ? trans('admin/alert.category.order_success'):trans('admin/alert.category.order_error')
			];
		} catch (Exception $e) {
			// 错误信息发送邮件
			DB::rollBack();
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
}