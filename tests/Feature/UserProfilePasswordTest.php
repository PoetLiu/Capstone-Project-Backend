<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;

use function PHPUnit\Framework\assertEquals;

class UserProfilePasswordTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/profile/password";

    public function test_update_profile_pwd(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI, [
            "old_password" => TestData::PWD, 
            "new_password" => TestData::NEW_PWD, 
            "new_password_confirmation" => TestData::NEW_PWD]
        );
        $response->assertStatus(200);
        $response->assertJsonPath('status', 0);
    }

    public function test_update_profile_pwd_missing_params(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI, [
        ]);
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }

    public function test_update_profile_basic_unauth(): void
    {
        $this->seed();
        $response = $this->post($this::URI, [
            "old_password" => TestData::PWD, 
            "new_password" => TestData::NEW_PWD, 
            "new_password_confirmation" => TestData::NEW_PWD]
        );
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }
}
