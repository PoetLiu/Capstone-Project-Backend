<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/user/profile";
    public function test_get_profile(): void
    {
        $this->seed();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get($this::URI);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
                ->has("data.id")
        );
    }

    public function test_get_profile_unauth(): void
    {
        $this->seed();
        $response = $this->get($this::URI);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
