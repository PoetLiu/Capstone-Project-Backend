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

class ProductAddTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/product/";
    public function test_add_product(): void
    {
        $this->seed();
        $user = User::where("is_admin", 1)->first();
        $category = Category::first();
        $data = [
            "brand" => TestData::PRODUCT_BRAND,
            "name" => TestData::PRODUCT_NAME,
            "description" => TestData::PRODUCT_DESC,
            "specifications" => TestData::PRODUCT_SPEC,
            "price" => TestData::PRODUCT_PRICE,
            "onsale_price" => TestData::PRODUCT_ONSALE_PRICE,
            "stock" => TestData::PRODUCT_STOCK,
            "is_featured" => true,
            "category_id" => $category->id,
            "image_url" => TestData::PRODUCT_IMG,
        ];
        $response = $this->actingAs($user)->post($this::URI, $data);
        $response->assertStatus(200);
        $response->assertJson(
            fn(AssertableJson $json) =>
            $json->hasAll(
                'data',
                "status",
                "msg",
            )
        );
    }

    public function test_add_product_exists(): void
    {
        $this->seed();
        $category = Category::first();
        $product = Product::first();
        $user = User::where("is_admin", 1)->first();
        $data = [
            "brand" => TestData::PRODUCT_BRAND,
            "name" => $product->name,
            "description" => TestData::PRODUCT_DESC,
            "specifications" => TestData::PRODUCT_SPEC,
            "price" => TestData::PRODUCT_PRICE,
            "onsale_price" => TestData::PRODUCT_ONSALE_PRICE,
            "stock" => TestData::PRODUCT_STOCK,
            "is_featured" => true,
            "category_id" => $category->id,
            "image_url" => TestData::PRODUCT_IMG,
        ];
        $response = $this->actingAs($user)->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }

    public function test_add_product_unauth(): void
    {
        $this->seed();
        $category = Category::first();
        $data = [
            "brand" => TestData::PRODUCT_BRAND,
            "name" => TestData::PRODUCT_NAME,
            "description" => TestData::PRODUCT_DESC,
            "specifications" => TestData::PRODUCT_SPEC,
            "price" => TestData::PRODUCT_PRICE,
            "onsale_price" => TestData::PRODUCT_ONSALE_PRICE,
            "stock" => TestData::PRODUCT_STOCK,
            "is_featured" => true,
            "category_id" => $category->id,
            "image_url" => TestData::PRODUCT_IMG,
        ];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }


    public function test_add_product_not_admin(): void
    {
        $this->seed();
        $category = Category::first();
        $user = User::where("is_admin", 0)->first();
        $data = [
            "brand" => TestData::PRODUCT_BRAND,
            "name" => TestData::PRODUCT_NAME,
            "description" => TestData::PRODUCT_DESC,
            "specifications" => TestData::PRODUCT_SPEC,
            "price" => TestData::PRODUCT_PRICE,
            "onsale_price" => TestData::PRODUCT_ONSALE_PRICE,
            "stock" => TestData::PRODUCT_STOCK,
            "is_featured" => true,
            "category_id" => $category->id,
            "image_url" => TestData::PRODUCT_IMG,
        ];
        $response = $this->actingAs($user)->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
