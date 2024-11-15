<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

use function PHPUnit\Framework\assertEquals;

class ProductDeleteTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/product/";
    public function test_delete_product(): void
    {
        $this->seed();
        $user = User::where("is_admin", 1)->first();
        $product = Product::first();
        $response = $this->actingAs($user)->delete($this::URI . $product->id);
        $response->assertStatus(200);

        $product = Product::find($product->id);
        $this->assertNull($product);
    }

    public function test_delete_not_exists(): void
    {
        $this->seed();
        $user = User::where("is_admin", 1)->first();
        $response = $this->actingAs($user)->delete($this::URI . random_int(1000000, 10000000));
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }

    public function test_edit_product_unauth(): void
    {
        $this->seed();
        $product = Product::first();
        $response = $this->delete($this::URI . $product->id);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }

    public function test_edit_product_not_admin(): void
    {
        $this->seed();
        $user = User::where("is_admin", 0)->first();
        $product = Product::first();
        $response = $this->actingAs($user)->delete($this::URI . $product->id);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
