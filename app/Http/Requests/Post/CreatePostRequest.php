<?php

namespace App\Http\Requests\Post;

use App\Models\Post\PostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:2',
            'content' => 'required|min:2',
            'images.*.src' => 'base64image',
            'status_id' => 'exists:post_statuses,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Введите название поста',
            'title.min' => 'Слишком короткое название поста',
            'content.required' => 'Введите текст поста',
            'content.min' => 'Слишком короткий текст поста',
            'status_id.exists' => 'Неверный статус',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $data = $this->all();

        if(!empty($data['published'])){
            $status = PostStatus::where('slug', 'opublikovan')->first();
        }else{
            $status = PostStatus::where('slug', 'cernovik')->first();
        }

        $this->merge([
            'status_id' => $status->id,
        ]);
    }
}
