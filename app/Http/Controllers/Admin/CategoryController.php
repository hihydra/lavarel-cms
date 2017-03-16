<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\CategoryService;
use App\Http\Requests\CategoryRequest;
class categoryController extends Controller
{
    private $category;

    public function __construct(CategoryService $category)
    {
        // 自定义权限中间件
        $this->middleware('check.permission:category');
        $this->category = $category;
    }

    /**
     * 分类列表
     * @author 晚黎
     * @date   2016-11-04T09:17:47+0800
     * @return [type]                   [description]
     */
    public function index()
    {
        $categorys = $this->category->getcategoryList();
        return view('admin.category.list')->with(compact('categorys'));
    }

    /**
     * 添加分类视图
     * @author 晚黎
     * @date   2016-11-04T09:53:36+0800
     * @return [type]                   [description]
     */
    public function create()
    {
        $categorys = $this->category->getCategoryList();
        return view('admin.category.create')->with(compact('categorys'));
    }

    /**
     * 添加分类
     * @author 晚黎
     * @date   2016-11-04T15:08:20+0800
     * @param  categoryRequest              $request [description]
     * @return [type]                            [description]
     */
    public function store(CategoryRequest $request)
    {
        $responseData = $this->category->storeCategory($request->all());
        return response()->json($responseData);
    }

    /**
     * 查看分类详细数据
     * @author 晚黎
     * @date   2016-11-04
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function show($id)
    {
        $categorys = $this->category->getCategoryList();
        $category = $this->category->findCategoryById($id);
        return view('admin.category.show')->with(compact('category','categorys'));
    }

    /**
     * 修改分类视图
     * @author 晚黎
     * @date   2016-11-04T16:26:53+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function edit($id)
    {
        $category = $this->category->findCategoryById($id);
        $categorys = $this->category->getCategoryList();
        return view('admin.category.edit')->with(compact('category','categorys'));
    }

    /**
     * 修改分类数据
     * @author 晚黎
     * @date   2016-11-04T17:57:32+0800
     * @param  categoryRequest              $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(categoryRequest $request, $id)
    {
        $responseData = $this->category->updateCategory($request->all(),$id);
        return response()->json($responseData);
    }

    /**
     * 删除分类
     * @author 晚黎
     * @date   2016-11-04
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function destroy($id)
    {
        $this->category->destroyCategory($id);
        return redirect('admin/category');
    }

    public function orderable()
    {
        $responseData = $this->category->orderable(request('nestable',''));
        return response()->json($responseData);
    }
}
