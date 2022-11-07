<?php

namespace App\Auth\Actions;

use App\Auth\DTOs\RegisterUserDTO;
use App\Auth\Exceptions\UserAlreadyExistException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function __;

class RegisterUserAction
{
    /**
     * @throws UserAlreadyExistException
     */
    public function execute(RegisterUserDTO $dto): User
    {
        if (User::whereEmail($dto->email)->exists()) {
            throw new UserAlreadyExistException();
        }

        $user = new User();
        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password);
        $user->save();

        $user->refresh();

        return $user;
    }
}
