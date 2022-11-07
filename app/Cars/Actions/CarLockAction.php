<?php

namespace App\Cars\Actions;

use App\Cars\Exceptions\CarAccessException;
use App\Cars\Exceptions\CarAnotherLockedException;
use App\Models\Car;
use App\Models\User;

class CarLockAction
{
    public function execute(User $user, Car $car): bool
    {
        /** @var ?Car $userCar */
        $userCar = $user->car;

        if ($userCar !== null && $userCar->id !== $car->id) {
            throw new CarAnotherLockedException();
        }

        if ($car->user_id !== null && $car->user_id !== $user->id) {
            throw new CarAccessException();
        }

        $car->user_id = $user->id;
        return $car->save();
    }
}
