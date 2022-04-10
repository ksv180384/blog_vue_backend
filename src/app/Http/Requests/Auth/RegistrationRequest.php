<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
        return [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required|confirmed|min:6',
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
            'email.required' => 'Заполните поле Email.',
            'email.email' => 'Некорректный email.',
            'email.unique' => 'Такой email уже зарегистрирован.',
            'name.required' => 'Введите ваше имя.',
            'password.required' => 'Введите пароль.',
            'password.confirmed' => 'Неверно подтвержден пароль.',
            'password.min' => 'Пароль должен состоять минимум из :min символов.',
        ];
    }

}
