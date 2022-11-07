<?php

namespace App\Auth\DTOs;

use Illuminate\Http\Request;

class RegisterUserDTO
{
    public string $email;

    public string $password;

    public string $name;

    static function fromRequest(Request $request): self
    {
        $dto = new self();

        $dto->email = $request->get('email');
        $dto->password = $request->get('password');
        $dto->name = $request->get('name');

        return $dto;
    }
}
