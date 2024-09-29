<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/login";
    public function test_login(): void
    {
        $this->seed();
        $data = [
            "username" => TestData::USER_NAME, "email" => TestData::EMAIL, "password" => TestData::PWD];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
                ->has("data.token")
        );
    }
}
