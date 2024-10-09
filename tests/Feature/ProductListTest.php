<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;

use function PHPUnit\Framework\assertEquals;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/product";
    public function test_list_product(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->get($this::URI);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
        );
    }

    public function test_list_product_onsale(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->get($this::URI);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
        );
    }

    public function test_list_product_unauth(): void
    {
        $this->seed();
        $response = $this->get($this::URI);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
