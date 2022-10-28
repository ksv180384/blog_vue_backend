<?php

namespace App\Http\Requests\Post;

use App\Models\Post\PostCommentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePostCommentRequest extends FormRequest
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
            'post_id' => 'required|exists:posts,id',
            'branch_id' => 'nullable|exists:post_comments,id',
            'parent_id' => 'nullable|exists:post_comments,id',
            'comment' => 'required|min:2',
            'author_id' => 'required',
            'status_id' => 'required',
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
            'post_id.required' => 'Пост не задан',
            'post_id.exists' => 'Неверный пост',
            'branch_id.exists' => 'Невернй родительский комментарий',
            'parent_id.exists' => 'Невернй родительский комментарий',
            'comment.required' => 'Комментарий не должен быть пустым',
            'comment.min' => 'Минимальный размер комментария :min',
            'author_id.required' => 'Неверно задан автор.',
            'status_id.required' => 'Неверно задан статус.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $status = PostCommentStatus::where('slug', 'opublikovan')->first();
        $this->merge([
            'author_id' => Auth::id(),
            'status_id' => $status->id,
        ]);
    }

}
