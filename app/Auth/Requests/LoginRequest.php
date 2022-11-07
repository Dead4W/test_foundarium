<?php

namespace App\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
