<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login(): void
    {
        $this->seed();
        $data = [
            "username" => USER_NAME, "email" => EMAIL, "password" => PWD];
        $response = $this->post(URI_USER . 'login', $data);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
                ->has("data.token")
        );
    }
}
