<?php
namespace App\Repositories\Eloquent;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Contracts\CategoryRepository;
use App\Models\Category;
/**
 * 分类仓库
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    public function allCategories()
    {
    	return $this->model->orderBy('sort','desc')->get()->toArray();
    }

    public function createCategory($attributes)
    {
        $model = new $this->model;
        return $model->fill($attributes)->save();
    }
}
