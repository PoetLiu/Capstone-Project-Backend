<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;
use App\Models\Category;

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
        $response = $this->actingAs($user)->get($this::URI . "?is_onsale=true");
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
        )->assertJsonPath('data.*.onsale_price', fn ($data) => empty(array_filter($data, function ($a) { return $a === null;})));
    }

    public function test_list_product_featured(): void
    {
        $this->seed();
        $user = User::first();
        $response = $this->actingAs($user)->get($this::URI . "?is_featured=true");
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->hasAll('data', "status", "msg")
        )->assertJsonPath('data.*.is_featured', fn ($data) => !in_array(0, $data));
    }

    public function test_list_product_unauth(): void
    {
        $this->seed();
        $response = $this->get($this::URI);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
