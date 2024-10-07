<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Data\TestData;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class UserResetPwdTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/reset-password";
    public function test_reset_password(): void
    {
        $this->seed();
        $user = User::first();
        $token = Password::broker()->createToken($user);
        $response = $this->post($this::URI, ["email" => TestData::EMAIL, "password" => TestData::PWD, "token" => $token]);
        $response->assertStatus(200);
        $response->assertJsonPath("status", 0);
    }

    public function test_reset_password_unauth(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->post($this::URI, ["email" => TestData::EMAIL, "password" => TestData::PWD, "token" => "token"]);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
