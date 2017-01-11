<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\TagService;
use App\Http\Requests\TagRequest;
class TagController extends Controller
{
    private $tag;

    function __construct(TagService $tag)
    {
        // 自定义权限中间件
        $this->middleware('check.permission:tag');
        $this->tag = $tag;
    }

    /**
     * 标签列表
     * @author 晚黎
     * @date   2016-11-03T11:50:59+0800
     * @return [type]                   [description]
     */
    public function index()
    {
        return view('admin.tag.list');
    }

    public function ajaxIndex()
    {
        $responseData = $this->tag->ajaxIndex();
        return response()->json($responseData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tag.create');
    }

    /**
     * 添加标签
     * @author 晚黎
     * @date   2016-11-03T15:14:56+0800
     * @param  tagRequest              $request [description]
     * @return [type]                            [description]
     */
    public function store(TagRequest $request)
    {
        $this->tag->storeTag($request->all());
        return redirect('admin/tag');
    }

    /**
     * 修改标签视图
     * @author 晚黎
     * @date   2016-11-03T16:41:48+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function edit($id)
    {
        $tag = $this->tag->editView($id);
        return view('admin.tag.edit')->with(compact('tag'));
    }

    /**
     * 修改标签
     * @author 晚黎
     * @date   2016-11-03T16:10:02+0800
     * @param  tagRequest              $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(tagRequest $request, $id)
    {
        $this->tag->updatetag($request->all(),$id);
        return redirect('admin/tag');
    }

    /**
     * 删除标签
     * @author 晚黎
     * @date   2016-11-03T17:20:49+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function destroy($id)
    {
        $this->tag->destroytag($id);
        return redirect('admin/tag');
    }
}
