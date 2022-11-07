<?php

namespace App\Cars\Actions;

use App\Cars\Exceptions\CarAccessException;
use App\Models\Car;
use App\Models\User;

class CarUnlockAction
{
    public function execute(User $user, Car $car): bool
    {
        /** @var ?Car $userCar */
        $userCar = $user->car;

        if ($userCar === null && $car->user_id === null) {
            return true;
        }

        if ($userCar?->id !== $car->id) {
            throw new CarAccessException();
        }

        $car->user_id = null;
        return $car->save();
    }
}
