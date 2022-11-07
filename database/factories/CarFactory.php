<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    private const CAR_COMPANIES = [
        'BMW',
        'AUDI',
        'TAYOTA'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $company = fake()->randomElement(self::CAR_COMPANIES);
        $modelFamily = fake()->randomLetter();
        $modelNumber = random_int(100, 999);

        return [
            'company' => $company,
            'model_family' => $modelFamily,
            'model_number' => $modelNumber,
        ];
    }
}
