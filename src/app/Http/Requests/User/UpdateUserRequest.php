<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|min:2',
            'avatar' => 'nullable|sometimes|image|mimes:jpg,png,jpeg|max:5120',
            'about' => 'nullable|sometimes|min:2',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Введите ваше имя.',
            'name.min' => 'Имя не должно быть короче :min символов.',
            'avatar.mimes' => 'Разрешенные изображения jpg,png,jpeg.',
            'avatar.max' => 'Максимальный размер изображения 5 mb.',
            'about.min' => '"О себе" должно быть не короче :min символов.',
        ];
    }
}
