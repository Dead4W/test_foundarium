<?php

namespace App\Common\Exceptions;

interface ResponseableException
{
    public function getResponseMessage(): string;

    public function getResponseErrors(): array;

    public function getResponseCode(): int;
}
