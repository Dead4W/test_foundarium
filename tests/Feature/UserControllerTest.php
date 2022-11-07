<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_current_user()
    {
        $user = $this->getRandomUser();

        $r = $this
            ->actingAs($user)
            ->get('/api/user');
        $r->assertStatus(200);

        $data = $r->json();

        $this->assertResult($data);

        $this->assertArrayHasKey('user', $data['result']);

        $this->assertUserResourceValid($data['result']['user']);

        $this->assertSame($data['result']['user']['uuid'], $user->uuid);
    }

    public function test_users_list()
    {
        $response = $this
            ->actingAs($this->getRandomUser())
            ->get('/api/users');

        $response->assertStatus(200);

        $data = $response->json();

        $this->assertPaginationResult($data, 'users');

        $page = 1;

        while(count($data['result']['users']) > 0) {
            $page++;
            $response = $this
                ->actingAs($this->getRandomUser())
                ->get("/api/users?page=$page");

            $response->assertStatus(200);

            $data = $response->json();

            $this->assertPaginationResult($data, 'users');
        }
    }

    public function test_user_by_id()
    {
        $user = $this->getRandomUser();

        $r = $this
            ->actingAs($this->getRandomUser())
            ->get('/api/users/' . $user->uuid);

        $r->assertStatus(200);

        $data = $r->json();

        $this->assertNotEmpty($data['result']['user'] ?? []);

        $this->assertUserResourceValid($data['result']['user']);

        $this->assertSame($data['result']['user']['uuid'], $user->uuid);
    }

    protected function assertUserResourceValid(array $data)
    {
        $this->assertIsArray($data);

        $this->assertArrayHasKey('uuid', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('name', $data);

        $this->assertNotEmpty($data['uuid']);
        $this->assertNotEmpty($data['email']);
        $this->assertNotEmpty($data['name']);
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
        $countItems = max(0, min($data['result']['limit'], $data['result']['total'] - $offset));

        foreach ($data['result'][$itemsKey] as $user) {
            $this->assertUserResourceValid($user);
        }

        $this->assertCount($countItems, $data['result'][$itemsKey]);
    }

    protected function assertResult(array $data)
    {
        $this->assertArrayHasKey('result', $data);
        $this->assertNotEmpty($data['result']);
    }

    protected function getRandomUser(): User
    {
        return User::query()->inRandomOrder()->first();
    }
}
