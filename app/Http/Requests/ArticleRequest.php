<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class ArticleRequest extends FormRequest
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
        $rules['title'] = 'required';
        $rules['category_id'] = 'numeric|required';
        $rules['editor-markdown-doc'] = 'required';
        // 添加权限
        if (request()->isMethod('PUT') || request()->isMethod('PATH')) {
            // 修改时 request()->method() 方法返回的是 PUT或PATCH
            $rules['id'] = 'numeric|required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'required'  => trans('validation.required'),
            'unique'    => trans('validation.unique'),
            'numeric'   => trans('validation.numeric'),
        ];
    }

    public function attributes()
    {
        return [
            'id'                  => trans('admin/article.model.id'),
            'title'               => trans('admin/article.model.title'),
            'category_id'         => trans('admin/article.model.id'),
            'intro'               => trans('admin/article.model.intro'),
            'img'                 => trans('admin/article.model.img'),
            'editor-markdown-doc' => trans('admin/article.model.content'),
            //'status'              => trans('admin/article.model.status'),
        ];
    }
}
