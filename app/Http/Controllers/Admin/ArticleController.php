<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\ArticleService;
use App\Service\Admin\UploadService;
use App\Http\Requests\ArticleRequest;
class ArticleController extends Controller
{
    private $article;
    private $upload;

    public function __construct(ArticleService $article,UploadService $upload)
    {
        // 自定义权限中间件
        $this->middleware('check.permission:article');
        $this->article = $article;
        $this->upload = $upload;
    }

    public function index()
    {
    	return view('admin.article.list');
    }

    public function ajaxIndex()
    {
    	$data = $this->article->ajaxIndex();
    	return response()->json($data);
    }

    /**
     * 添加文章视图
     * @author 晚黎
     * @date   2016-04-12T09:56:23+0800
     * @return [type]                   [description]
     */
    public function create()
    {
        list($categories,$tags) = $this->article->createView();
    	return view('admin.article.create')->with(compact(['tags','categories']));
    }

    /**
     * 添加文章
     * @date   2016-05-06
     * @author 晚黎
     * @param  ArticleRequest $request [description]
     * @return [type]                  [description]
     */
    public function store(ArticleRequest $request)
    {
    	$this->article->storeArticle($request->all());
    	return redirect('admin/article');
    }

    /**
     * 修改文章视图
     * @author 晚黎
     * @date   2016-05-08T11:00:11+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function edit($id)
    {
        list($categories,$tags,$article) = $this->article->editView($id);
    	return view('admin.article.edit')->with(compact(['article','tags','categories']));
    }

    /**
     * 修改文章
     * @author 晚黎
     * @date   2016-05-08T11:00:37+0800
     * @param  ArticleRequest           $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(ArticleRequest $request,$id)
    {
    	$this->article->updateArticle($request->all(),$id);
        return redirect('admin/article');
    }

    /**
     * 修改文章状态
     * @author 晚黎
     * @date   2016-05-08T11:00:53+0800
     * @param  [type]                   $id     [description]
     * @param  [type]                   $status [description]
     * @return [type]                           [description]
     */
    public function mark($id,$status)
    {
    	ArticleRepository::mark($id,$status);
        return redirect('admin/article');
    }

    /**
     * 删除文章
     * @author 晚黎
     * @date   2016-05-08T11:01:06+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function destroy($id)
    {
        $this->article->destroyArticle($id);
        return redirect('admin/article');
    }

    /**
     * 查看文章信息
     * @author 晚黎
     * @date   2016-05-12T09:41:40+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function show($id)
    {
        return redirect('article/'.$id);
    }

    /**
     * markdown上传图片
     * @author 晚黎
     * @date   2016-05-12T09:42:04+0800
     * @param  Request                  $request [description]
     * @return [type]                            [description]
     */
    public function upload(Request $request)
    {
        $response = $this->upload->uploadImage($request);
        return response()->json($response);
    }
}
