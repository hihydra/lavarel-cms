<?php
namespace App\Service\Admin;
use App\Repositories\Eloquent\ArticleRepositoryEloquent;
use App\Service\Admin\CategoryService;
use App\Repositories\Eloquent\TagRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
/**
* 角色service
*/
class ArticleService extends BaseService
{

	private $article;

	function __construct(ArticleRepositoryEloquent $article,CategoryService $category,TagRepositoryEloquent $tag)
	{
		$this->article  =  $article;
		$this->category =  $category;
		$this->tag      =  $tag;
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

		$result = $this->article->getArticleList($start,$length,$search,$order);

		$articles = [];

		if ($result['articles']) {
			foreach ($result['articles'] as $v) {
				$v->actionButton = $v->getActionButtonAttribute(false);
				$v->status = $v->getStatusTextAttribute($v->status);
				$articles[] = $v;
			}
		}

		return [
			'draw' => $draw,
			'recordsTotal' => $result['count'],
			'recordsFiltered' => $result['count'],
			'data' => $articles,
		];
	}
	/**
	 * 创建文章视图数据
	 * @author 晚黎
	 * @date   2016-11-03T13:29:53+0800
	 * @return [type]                   [description]
	 */
	public function createView()
	{
		return [$this->getAllCategories(),$this->getAllTags()];
	}
	/**
	 * 获取所有分类
	 * @author 晚黎
	 * @date   2016-11-03T13:30:13+0800
	 * @return [type]                   [description]
	 */
	public function getAllCategories()
	{
		return $this->category->getCategoryList();
	}
	/**
	 * 获取所有标签
	 * @author 晚黎
	 * @date   2016-11-03T13:23:46+0800
	 * @return [type]                   [description]
	 */
	public function getAllTags()
	{
		return $this->tag->all(['id','name']);
	}
	/**
	 * 添加文章
	 * @author 晚黎
	 * @date   2016-11-03T15:16:00+0800
	 * @param  [type]                   $formData [表单中所有的数据]
	 * @return [type]                             [Boolean]
	 */
	public function storeArticle($formData)
	{
		try {
			//if ($request->hasFile('img')) {
			//	$data['img'] = $this->uploadImage($request->file('img'));
			//}
			$formData['content_html'] = $formData['editor-html-code'];
			$formData['content_mark'] = $formData['editor-markdown-doc'];
			$result = $this->article->create($formData);
			if ($result) {
				// 文章与标签关系
				if ($formData['tags']) {
					$result->tag()->sync($formData['tags']);
				}
			}
			flash_info($result,trans('admin/alert.article.create_success'),trans('admin/alert.article.create_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
	/**
	 * 编辑文章视图所需数据
	 * @author 晚黎
	 * @date   2016-11-03T15:52:46+0800
	 * @param  [type]                   $id [文章ID]
	 * @return [type]                       [description]
	 */
	public function editView($id)
	{
		$article = $this->article->with('tag')->find($id);
		if ($article){
			if ($article->tag) {
				$tagIds = array_column($article->tag->toArray(), 'id');
				$article->tag = $tagIds;
			}
			return [$this->getAllCategories(),$this->getAllTags(),$article];
		}
		// TODO替换正查找不到数据错误页面
		abort(404);
	}
	/**
	 * 修改文章
	 * @author 晚黎
	 * @date   2016-11-03T16:12:05+0800
	 * @param  [type]                   $attributes [表单数据]
	 * @param  [type]                   $id         [resource路由传递过来的id]
	 * @return [type]                               [Boolean]
	 */
	public function updateArticle($attributes,$id)
	{
		// 防止文章恶意修改表单id，如果id不一致直接跳转500
		if ($attributes['id'] != $id) {
			abort(500,trans('admin/errors.article_error'));
		}
		try {
			$result = $this->article->update($attributes,$id);
			if ($result) {
				// 文章与标签关系
				if ($attributes['tags']) {
					$result->tag()->sync($attributes['tags']);
				}
			}
			flash_info($result,trans('admin/alert.article.edit_success'),trans('admin/alert.article.edit_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}

	/**
	 * 删除文章
	 * @author 晚黎
	 * @date   2016-11-03T16:33:12+0800
	 * @param  [type]                   $id [标签ID]
	 * @return [type]                       [Boolean]
	 */
	public function destroyArticle($id)
	{
		try {
			$result = $this->article->find($id);
			if ($result) {
				$result->tag()->detach();
        		$result->delete();
			}
			flash_info($result,trans('admin/alert.article.destroy_success'),trans('admin/alert.article.destroy_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}

	}

}