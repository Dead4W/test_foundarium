<?php

namespace Tests\Feature;

use App\Cars\Enums\CarStateEnum;
use App\Models\Car;
use App\Models\User;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    public function test_current_without_car()
    {
        $user = $this->getRandomUserWithoutCar();

        $r = $this
            ->actingAs($user)
            ->get('/api/user/car');
        $r->assertStatus(200);

        $data = $r->json();

        $this->assertResult($data);

        $this->assertArrayHasKey('car', $data['result']);
        $this->assertNull($data['result']['car']);
    }

    public function test_current_car()
    {
        $user = $this->getRandomUserWithCar();

        $r = $this
            ->actingAs($user)
            ->get('/api/user/car');
        $r->assertStatus(200);

        $data = $r->json();

        $this->assertResult($data);

        $this->assertArrayHasKey('car', $data['result']);

        $this->assertCarResourceValid($data['result']['car'], CarStateEnum::BUSY);

        $this->assertSame($data['result']['car']['uuid'], $user->car->uuid);
    }

    public function test_car_by_id()
    {
        $car = $this->getRandomCar();

        $r = $this
            ->actingAs($this->getRandomUser())
            ->get('/api/cars/' . $car->uuid);

        $r->assertStatus(200);

        $data = $r->json();

        $carState = !empty($car->user_id) ? CarStateEnum::BUSY : CarStateEnum::FREE;

        $this->assertNotEmpty($data['result']['car'] ?? []);
        $this->assertCarResourceValid($data['result']['car'], $carState);

        $this->assertSame($data['result']['car']['uuid'], $car->uuid);
    }

    public function test_car_lock()
    {
        // test error when car is busy
        $car = $this->getRandomBusyCar();
        $r = $this
            ->actingAs($this->getRandomUserWithoutCar())
            ->post("/api/cars/{$car->uuid}/lock");
        $r->assertStatus(422);

        // test error when user already have car
        $car = $this->getRandomFreeCar();
        $r = $this
            ->actingAs($this->getRandomUserWithCar())
            ->post("/api/cars/{$car->uuid}/lock");
        $r->assertStatus(422);

        //test success lock
        $user = $this->getRandomUserWithoutCar();
        $car = $this->getRandomFreeCar();

        $r = $this
            ->actingAs($user)
            ->post("/api/cars/{$car->uuid}/lock");
        $r->assertStatus(200);

        $data = $r->json();

        $this->assertResult($data);

        $this->assertArrayHasKey('status', $data['result']);
        $this->assertTrue($data['result']['status']);

        $this->assertTrue($user->car()->whereId($car->id)->exists());
    }

    public function test_car_unlock()
    {
        // test error user don't have car
        $user = $this->getRandomUserWithoutCar();
        $car = $this->getRandomBusyCar();
        $r = $this
            ->actingAs($user)
            ->post("/api/cars/{$car->uuid}/unlock");
        $r->assertStatus(422);

        // test error when car is not owned by user
        $user = $this->getRandomUserWithCar();
        $car = Car::where('user_id', '!=', $user->id)->first();
        $r = $this
            ->actingAs($user)
            ->post("/api/cars/{$car->uuid}/unlock");
        $r->assertStatus(422);

        // test success
        $user = $this->getRandomUserWithCar();
        $r = $this
            ->actingAs($user)
            ->post("/api/cars/{$user->car->uuid}/unlock");
        $r->assertStatus(200);

        $data = $r->json();

        $this->assertResult($data);

        $this->assertArrayHasKey('status', $data['result']);
        $this->assertTrue($data['result']['status']);

        $this->assertFalse($user->car()->exists());
    }

    public function test_cars_list()
    {
        $r = $this
            ->actingAs($this->getRandomUser())
            ->get('/api/cars');
        $r->assertStatus(200);

        $data = $r->json();

        $this->assertPaginationResult($data, 'cars');

        $page = 1;

        while (count($data['result']['cars']) > 0) {
            $page++;
            $response = $this
                ->actingAs($this->getRandomUser())
                ->get("/api/cars?page=$page");

            $response->assertStatus(200);

            $data = $response->json();

            $this->assertPaginationResult($data, 'cars');
        }
    }

    protected function assertCarResourceValid(array $data, string $state = null)
    {
        $fields = [
            'uuid',
            'company',
            'model_family',
            'model_number',
            'state',
        ];

        $this->assertIsArray($data);

        foreach ($fields as $field) {
            $this->assertArrayHasKey($field, $data);
            $this->assertNotEmpty($data[$field]);
        }

        $this->assertSame($data['state'], $state);
    }

    protected function assertPaginationResult(array $data, string $itemsKey)
    {
        $this->assertResult($data);

        $this->assertArrayHasKey($itemsKey, $data['result']);

        $this->assertIsArray($data['result'][$itemsKey]);

        $this->assertArrayHasKey('total', $data['result']);
        $this->assertArrayHasKey('limit', $data['result']);
        $this->assertArrayHasKey('page', $data['result']);

        $offset = ($data['result']['page'] - 1) * $data['result']['limit'];
        $countItems = max(min($data['result']['limit'], $data['result']['total'] - $offset), 0);

        foreach ($data['result'][$itemsKey] as $user) {
            $this->assertCarResourceValid($user, CarStateEnum::FREE);
        }

        $this->assertCount($countItems, $data['result'][$itemsKey]);
    }

    protected function assertResult(array $data)
    {
        $this->assertArrayHasKey('result', $data);
        $this->assertNotEmpty($data['result']);
    }

    protected function getRandomUserWithCar(): User
    {
        return User::query()->has('car')->inRandomOrder()->first();
    }

    protected function getRandomUserWithoutCar(): User
    {
        return User::query()->whereDoesntHave('car')->inRandomOrder()->first();
    }

    protected function getRandomUser(): User
    {
        return User::query()->inRandomOrder()->first();
    }

    protected function getRandomCar(): Car
    {
        return Car::query()->inRandomOrder()->first();
    }

    protected function getRandomFreeCar(): Car
    {
        return Car::query()->whereNull('user_id')->inRandomOrder()->first();
    }

    protected function getRandomBusyCar(): Car
    {
        return Car::query()->whereNotNull('user_id')->first();
    }
}
