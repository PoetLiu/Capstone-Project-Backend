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

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/product/";
    public function test_product_detail(): void
    {
        $this->seed();
        $user = User::first();
        $product = Product::first();
        $response = $this->actingAs($user)->get($this::URI . $product->id);
        $response->assertStatus(200);
        $response->assertJson(
            fn(AssertableJson $json) =>
            $json->hasAll(
                'data',
                "status",
                "msg",
                "data.id",
                "data.brand",
                "data.name",
                "data.description",
                "data.specifications",
                "data.price",
                "data.onsale_price",
                "data.stock",
                "data.is_featured",
                "data.category_id",
                "data.image_url",
                "data.created_at",
                "data.updated_at"
            )
        );
    }

    public function test_product_detail_unauth(): void
    {
        $this->seed();
        $product = Product::first();
        $response = $this->get($this::URI . $product->id);
        $response->assertStatus(200);
        $response->assertJsonPath("status", 0);
    }

    public function test_product_detail_unknown(): void
    {
        $this->seed();
        $productId = random_int(10000, 100000);
        $response = $this->get($this::URI . $productId);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
