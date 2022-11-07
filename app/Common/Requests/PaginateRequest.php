<?php

namespace App\Common\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginateRequest extends FormRequest
{
    protected const DEFAULT_PAGE = 1;
    protected const DEFAULT_LIMIT = 10;

    public function rules()
    {
        return [
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:100',
        ];
    }

    public function getPage(): int
    {
        return $this->get('page', self::DEFAULT_PAGE);
    }

    public function getPerPage(): int
    {
        return $this->get('limit', self::DEFAULT_LIMIT);
    }
}
