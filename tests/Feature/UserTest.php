<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

define("URI_USER", "/api/user/");
define("EMAIL", "test@gmail.com");
define("INVALID_EMAIL", "@gmail.com");
define("USER_NAME", "test");
define("PWD", "12345678");
class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_register_empty_body(): void
    {
        $response = $this->post(URI_USER . 'register', []);
        $response->assertStatus(400);
    }

    public function test_register_missing_parameters(): void
    {
        $response = $this->post(URI_USER . 'register', [
            "username" => USER_NAME]);
        $response->assertStatus(400);

        $response = $this->post(URI_USER . 'register', [
            "username" => USER_NAME, "email" => EMAIL]);
        $response->assertStatus(400);
    }

    public function test_register_invalid_parameters(): void
    {
        $response = $this->post(URI_USER . 'register', [
            "username" => USER_NAME, "email" => INVALID_EMAIL, "password" => PWD]);
        $response->assertStatus(400);
    }

    public function test_register_duplicated(): void
    {
        $data = [
            "username" => USER_NAME, "email" => EMAIL, "password" => PWD];
        $response = $this->post(URI_USER . 'register', $data);
        $response->assertStatus(200);

        $response = $this->post(URI_USER . 'register', $data);
        $response->assertStatus(400);
    }

    public function test_register(): void
    {
        $data = [
            "username" => USER_NAME, "email" => EMAIL, "password" => PWD];
        $response = $this->post(URI_USER . 'register', $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => EMAIL,
        ]);

        $where = User::where('email', EMAIL);
        $this->assertTrue($where->exists());

        $user = $where->first();
        $this->assertEquals(USER_NAME, $user->username);
        
        $this->assertTrue(Hash::check(PWD, $user->password));
    }
}
