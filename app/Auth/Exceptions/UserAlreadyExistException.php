<?php

namespace App\Auth\Exceptions;

use App\Common\Exceptions\ResponseableException;
use Exception;

class UserAlreadyExistException extends Exception implements ResponseableException
{

    public function getResponseMessage(): string
    {
        return __('auth.register.email_already_exists');
    }

    public function getResponseErrors(): array
    {
        return [
            'email' => [$this->getResponseMessage()],
        ];
    }

    public function getResponseCode(): int
    {
        return 422;
    }
}
