<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;

use function PHPUnit\Framework\assertEquals;

class UserProfileBasicTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/profile/basic";

    public function test_update_profile_basic(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI, 
            ["username" => TestData::NEW_USER_NAME, "email" => TestData::NEW_EMAIL, "phone" => TestData::PHONE]
        );
        $response->assertStatus(200);
        $response->assertJsonPath('status', 0);
        $this->assertEquals($user->email, TestData::NEW_EMAIL);
        $this->assertEquals($user->phone, TestData::PHONE);
        $this->assertEquals($user->username, TestData::NEW_USER_NAME);
    }

    public function test_update_profile_basic_missing_params(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->post($this::URI,
            []
        );
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }

    public function test_update_profile_basic_unauth(): void
    {
        $this->seed();
        $response = $this->post($this::URI, 
            ["username" => TestData::NEW_USER_NAME, "email" => TestData::NEW_EMAIL, "phone" => TestData::PHONE]
        );
        $response->assertStatus(400);
        $response->assertJsonPath('status', 1);
    }
}
