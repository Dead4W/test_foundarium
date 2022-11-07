<?php

namespace App\Cars\Queries;

use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;

class CarsQuery
{
    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function getFreeCarQuery(): Builder
    {
        return Car::whereNull('user_id');
    }
}
