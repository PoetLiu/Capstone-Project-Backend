<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;
use App\Models\Category;

use function PHPUnit\Framework\assertEquals;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/dashboard/summary";
    public function test_dashboard_summary(): void
    {
        $this->seed();
        $user = User::where("is_admin", 1)->first();
        $response = $this->actingAs($user)->get($this::URI);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg", 
                'data.users_count', 'data.orders_count', 'data.categories_count', 'data.products_count', 'data.reviews_count')
        );
    }

    public function test_dashboard_summary_unauth(): void
    {
        $this->seed();
        $response = $this->get($this::URI);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
