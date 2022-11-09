<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email:dns|unique:users,email',
            'name' => 'required',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
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
