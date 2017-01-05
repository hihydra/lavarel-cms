<?php
namespace App\Repositories\Eloquent;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Contracts\TagRepository;
use App\Models\Tag;
/**
 * Class TagRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class TagRepositoryEloquent extends BaseRepository implements TagRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tag::class;
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
    public function getTagList($start,$length,$search,$order)
    {
        $tag = $this->model;
        if ($search['value']) {
            if($search['regex'] == 'true'){
                $tag = $tag->where('name', 'like', "%{$search['value']}%")->orWhere('tagname','like', "%{$search['value']}%");
            }else{
                $tag = $tag->where('name', $search['value'])->orWhere('tagname', $search['value']);
            }
        }

        $count = $tag->count();

        $tag = $tag->orderBy($order['name'], $order['dir']);

        $tags = $tag->offset($start)->limit($length)->get();

        return compact('count','tags');
    }

}
