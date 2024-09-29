<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use Tests\Data\TestData;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;
    const URI = "/api/user/register";
    /**
     * A basic feature test example.
     */
    public function test_register(): void
    {
        $data = [
            "username" => TestData::USER_NAME, "email" => TestData::EMAIL, "password" => TestData::PWD];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            "email" => TestData::EMAIL,
        ]);

        $where = User::where("email", TestData::EMAIL);
        $this->assertTrue($where->exists());

        $user = $where->first();
        $this->assertEquals(TestData::USER_NAME, $user->username);
        
        $this->assertTrue(Hash::check(TestData::PWD, $user->password));
    }

    public function test_register_empty_body(): void
    {
        $response = $this->post($this::URI, []);
        $response->assertStatus(400);
    }

    public function test_register_missing_parameters(): void
    {
        $response = $this->post($this::URI, [
            "username" => TestData::USER_NAME]);
        $response->assertStatus(400);

        $response = $this->post($this::URI, [
            "username" => TestData::USER_NAME, "email" => TestData::EMAIL]);
        $response->assertStatus(400);
    }

    public function test_register_invalid_parameters(): void
    {
        $response = $this->post($this::URI, [
            "username" => TestData::USER_NAME, "email" => TestData::INVALID_EMAIL, "password" => TestData::PWD]);
        $response->assertStatus(400);
    }

    public function test_register_duplicated(): void
    {
        $data = [
            "username" => TestData::USER_NAME, "email" => TestData::EMAIL, "password" => TestData::PWD];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(200);

        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
    }
}
