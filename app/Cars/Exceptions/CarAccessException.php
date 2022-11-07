<?php

namespace App\Cars\Exceptions;

use App\Common\Exceptions\ResponseableException;
use Exception;

class CarAccessException extends Exception implements ResponseableException
{

    public function getResponseMessage(): string
    {
        return __('errors.car.cant_access');
    }

    public function getResponseErrors(): array
    {
        return [
            $this->getResponseMessage(),
        ];
    }

    public function getResponseCode(): int
    {
        return 422;
    }
}
