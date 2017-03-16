<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class TagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['name']      = 'required';
        // 添加权限
        if (request()->isMethod('PUT') || request()->isMethod('PATH')) {
            // 修改时 request()->method() 方法返回的是 PUT或PATCH
            $rules['id'] = 'numeric|required';
        }
        return $rules;
    }

    /**
     * 字段名称
     * @author 晚黎
     * @date   2016-11-03T14:52:38+0800
     * @return [type]                   [description]
     */
    public function attributes()
    {
        return [
            'id'        => trans('admin/tag.model.id'),
            'name'      => trans('admin/tag.model.name'),
            'slug'      => trans('admin/tag.model.slug'),
            'icon'      => trans('admin/tag.model.icon'),
        ];
    }
}
