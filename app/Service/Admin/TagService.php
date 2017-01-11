<?php
namespace App\Service\Admin;
use App\Repositories\Eloquent\TagRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
/**
* 角色service
*/
class TagService extends BaseService
{

	private $tag;

	function __construct(TagRepositoryEloquent $tag)
	{
		$this->tag =  $tag;
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

		$result = $this->tag->getTagList($start,$length,$search,$order);

		$tags = [];

		if ($result['tags']) {
			foreach ($result['tags'] as $v) {
				$v->actionButton = $v->getActionButtonAttribute(false);
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
	 * 添加标签
	 * @author 晚黎
	 * @date   2016-11-03T15:16:00+0800
	 * @param  [type]                   $formData [表单中所有的数据]
	 * @return [type]                             [Boolean]
	 */
	public function storeTag($formData)
	{
		try {
			$result = $this->tag->create($formData);
			flash_info($result,trans('admin/alert.tag.create_success'),trans('admin/alert.tag.create_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
	/**
	 * 编辑标签视图所需数据
	 * @author 晚黎
	 * @date   2016-11-03T15:52:46+0800
	 * @param  [type]                   $id [标签ID]
	 * @return [type]                       [description]
	 */
	public function editView($id)
	{
		$tag = $this->tag->find($id);
		if ($tag){
			return $tag;
		}
		// TODO替换正查找不到数据错误页面
		abort(404);
	}
	/**
	 * 修改标签
	 * @author 晚黎
	 * @date   2016-11-03T16:12:05+0800
	 * @param  [type]                   $attributes [表单数据]
	 * @param  [type]                   $id         [resource路由传递过来的id]
	 * @return [type]                               [Boolean]
	 */
	public function updatetag($attributes,$id)
	{
		// 防止标签恶意修改表单id，如果id不一致直接跳转500
		if ($attributes['id'] != $id) {
			abort(500,trans('admin/errors.tag_error'));
		}
		try {
			$result = $this->tag->update($attributes,$id);
			flash_info($result,trans('admin/alert.tag.edit_success'),trans('admin/alert.tag.edit_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
	/**
	 * 标签暂不做状态管理，直接删除
	 * @author 晚黎
	 * @date   2016-11-03T16:33:12+0800
	 * @param  [type]                   $id [标签ID]
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