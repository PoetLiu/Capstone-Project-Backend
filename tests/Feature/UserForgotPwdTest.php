<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Data\TestData;
use App\Models\User;

class UserForgotPwdTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/forgot-password";
    public function test_forgot_password(): void
    {
        $this->seed();
        $response = $this->post($this::URI, ["email" => TestData::EMAIL]);
        $response->assertStatus(200);
        $response->assertJsonPath("status", 0);

    }

    public function test_get_profile_missing_parameter(): void
    {
        $this->seed();
        $response = $this->post($this::URI, []);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
