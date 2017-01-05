<?php
namespace App\Service\Admin;
use App\Repositories\Eloquent\TagRepositoryEloquent;
use App\Repositories\Eloquent\RoleRepositoryEloquent;
use App\Repositories\Eloquent\PermissionRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
/**
* 角色service
*/
class TagService extends BaseService
{

	private $tag;
	private $role;
	private $permission;

	function __construct(TagRepositoryEloquent $tag,RoleRepositoryEloquent $role,PermissionRepositoryEloquent $permission)
	{
		$this->tag =  $tag;
		$this->role =  $role;
		$this->permission =  $permission;
	}
	/**
	 * datatables获取数据
	 * @author 晚黎
	 * @date   2016-11-02T10:31:46+0800
	 * @return [type]                   [description]
	 */
	public function ajaxIndex()
	{
		// datatables请求次数
		$draw = request('draw', 1);
		// 开始条数
		$start = request('start', config('admin.golbal.list.start'));
		// 每页显示数目
		$length = request('length', config('admin.golbal.list.length'));
		// datatables是否启用模糊搜索
		$search['regex'] = request('search.regex', false);
		// 搜索框中的值
		$search['value'] = request('search.value', '');
		// 排序
		$order['name'] = request('columns.' .request('order.0.column',0) . '.name');
		$order['dir'] = request('order.0.dir','asc');

		$result = $this->tag->gettagList($start,$length,$search,$order);

		$tags = [];

		if ($result['tags']) {
			foreach ($result['tags'] as $v) {
				$v->actionButton = $v->getActionButtonAttribute();
				$tags[] = $v;
			}
		}

		return [
			'draw' => $draw,
			'recordsTotal' => $result['count'],
			'recordsFiltered' => $result['count'],
			'data' => $tags,
		];
	}
	/**
	 * 创建用户视图数据
	 * @author 晚黎
	 * @date   2016-11-03T13:29:53+0800
	 * @return [type]                   [description]
	 */
	public function createView()
	{
		return [$this->getAllPermissionList(),$this->getAllRoles()];
	}
	/**
	 * 获取所有权限并分组
	 * @author 晚黎
	 * @date   2016-11-03T13:30:13+0800
	 * @return [type]                   [description]
	 */
	public function getAllPermissionList()
	{
		return $this->permission->groupPermissionList();
	}
	/**
	 * 获取所有的角色
	 * @author 晚黎
	 * @date   2016-11-03T13:23:46+0800
	 * @return [type]                   [description]
	 */
	public function getAllRoles()
	{
		return $this->role->all(['id','name']);
	}
	/**
	 * 添加用户
	 * @author 晚黎
	 * @date   2016-11-03T15:16:00+0800
	 * @param  [type]                   $formData [表单中所有的数据]
	 * @return [type]                             [Boolean]
	 */
	public function storetag($formData)
	{
		try {
			$result = $this->tag->create($formData);
			if ($result) {
				// 角色与用户关系
				if ($formData['role']) {
					$result->roles()->sync($formData['role']);
				}
				// 权限与用户关系
				if ($formData['permission']) {
					$result->tagPermissions()->sync($formData['permission']);
				}
			}
			flash_info($result,trans('admin/alert.tag.create_success'),trans('admin/alert.tag.create_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
	/**
	 * 编辑用户视图所需数据
	 * @author 晚黎
	 * @date   2016-11-03T15:52:46+0800
	 * @param  [type]                   $id [用户ID]
	 * @return [type]                       [description]
	 */
	public function editView($id)
	{
		return [$this->findtagById($id),$this->getAllPermissionList(),$this->getAllRoles()];
	}
	/**
	 * 根据ID获取权限数据
	 * @author 晚黎
	 * @date   2016-11-03T09:22:44+0800
	 * @param  [type]                   $id [权限id]
	 * @return [type]                       [查询出来的权限对象，查不到数据时跳转404]
	 */
	public function findtagById($id)
	{
		$role =  $this->tag->with(['tagPermissions','roles'])->find($id);
		if ($role) {
			return $role;
		}
		abort(404);
	}
	/**
	 * 修改用户
	 * @author 晚黎
	 * @date   2016-11-03T16:12:05+0800
	 * @param  [type]                   $attributes [表单数据]
	 * @param  [type]                   $id         [resource路由传递过来的id]
	 * @return [type]                               [Boolean]
	 */
	public function updatetag($attributes,$id)
	{
		// 防止用户恶意修改表单id，如果id不一致直接跳转500
		if ($attributes['id'] != $id) {
			abort(500,trans('admin/errors.tag_error'));
		}
		try {
			$result = $this->tag->update($attributes,$id);
			if ($result) {
				// 更新用户角色关系
				if (isset($attributes['role'])) {
					$result->roles()->sync($attributes['role']);
				}else{
					$result->roles()->sync([]);
				}
				// 更新用户权限关系
				if (isset($attributes['permission'])) {
					$result->tagPermissions()->sync($attributes['permission']);
				}else{
					$result->tagPermissions()->sync([]);
				}
			}
			flash_info($result,trans('admin/alert.tag.edit_success'),trans('admin/alert.tag.edit_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
	/**
	 * 用户暂不做状态管理，直接删除
	 * @author 晚黎
	 * @date   2016-11-03T16:33:12+0800
	 * @param  [type]                   $id [用户ID]
	 * @return [type]                       [Boolean]
	 */
	public function destroytag($id)
	{
		try {
			$result = $this->tag->delete($id);
			flash_info($result,trans('admin/alert.tag.destroy_success'),trans('admin/alert.tag.destroy_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}

	}
}