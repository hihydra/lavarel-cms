<?php
namespace App\Repositories\Eloquent;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Contracts\ArticleRepository;
use App\Models\Article;
/**
 * Class articleRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ArticleRepositoryEloquent extends BaseRepository implements ArticleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Article::class;
    }

    /**
     * 查询标签并分页
     * @author 晚黎
     * @date   2016-11-03T12:56:28+0800
     * @param  [type]                   $start  [起始数目]
     * @param  [type]                   $length [读取条数]
     * @param  [type]                   $search [搜索数组数据]
     * @param  [type]                   $order  [排序数组数据]
     * @return [type]                           [查询结果集，包含查询的数量及查询的结果对象]
     */
    public function getArticleList($start,$length,$search,$order)
    {
        $article = $this->model;
        if ($search['value']) {
            if($search['regex'] == 'true'){
                $article = $article->where('title', 'like', "%{$search['value']}%");
            }else{
                $article = $article->where('title', $search['value']);
            }
        }

        $count = $article->count();

        $article = $article->orderBy($order['title'], $order['dir']);

        $articles = $article->offset($start)->limit($length)->get();

        return compact('count','articles');
    }

}
