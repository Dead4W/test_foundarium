<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_auth()
    {
        $user = $this->generateUserData();

        $r = $this->post('/api/auth/register', $user);
        $r->assertStatus(200);

        $r = $this->post('/api/auth/login', $user);
        $r->assertStatus(200);

        $responseResult = $r->json()['result'];

        $this->assertNotEmpty($responseResult);

        $this->assertArrayHasKey('user', $responseResult);
        $this->assertArrayHasKey('access_token', $responseResult);
    }

    public function test_register()
    {
        $user = $this->generateUserData();

        $r = $this->post('/api/auth/register', $user);
        $r->assertStatus(200);

        $responseResult = $r->json()['result'];

        $this->assertNotEmpty($responseResult);

        $this->assertArrayHasKey('user', $responseResult);
        $this->assertArrayHasKey('access_token', $responseResult);

        $r = $this
            ->withHeaders(
                [
                    'Accept' => 'application/json',
                ]
            )
            ->post('/api/auth/register', $user);
        $r->assertStatus(422);

        $userModel = User::whereEmail($user['email'])->first();

        $this->assertNotNull($userModel);

        $this->assertCredentials($user);

        $this->assertTrue($userModel->name === $user['name']);
    }

    public function test_error_register()
    {
        $user = $this->generateUserData();
        $user['email'] = 'asdawdawdawdawdawd@dgfpijohdiogjdrfiogiodersg.dfiosgio';

        $r = $this
            ->withHeaders(
                [
                    'Accept' => 'application/json',
                ]
            )
            ->post('/api/auth/register', $user);

        $r->assertStatus(422);
    }

    private function generateUserData()
    {
        return [
            'email' => Str::random() . '@mail.ru',
            'password' => Str::random(),
            'name' => Str::random(),
        ];
    }
}
