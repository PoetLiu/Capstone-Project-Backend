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
            "email" => TestData::EMAIL, "password" => TestData::PWD];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
                ->has("data.token")
        );
    }

    public function test_login_unknown_email(): void
    {
        $this->seed();
        $data = [
            "email" => TestData::UNKNOWN_EMAIL, "password" => TestData::PWD];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }

    public function test_login_invalid_pwd(): void
    {
        $this->seed();
        $data = [
            "email" => TestData::EMAIL, "password" => TestData::INVALID_PWD];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }

    public function test_login_missing_parameters(): void
    {
        $this->seed();
        $data = [];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }

    public function test_login_missing_parameters_pwd(): void
    {
        $this->seed();
        $data = ["email" => TestData::EMAIL];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }

    public function test_login_missing_parameters_email(): void
    {
        $this->seed();
        $data = [
             "password" => TestData::INVALID_PWD];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }
}
