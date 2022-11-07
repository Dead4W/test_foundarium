<?php

namespace App\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|string|email:strict,dns',
            'password' => 'required|string|min:6',
        ];
    }
}
