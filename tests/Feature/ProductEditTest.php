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

class ProductEditTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/product/";
    public function test_edit_product(): void
    {
        $this->seed();
        $user = User::where("is_admin", 1)->first();
        $category = Category::first();
        $product = Product::first();
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
        $response = $this->actingAs($user)->post($this::URI . $product->id, $data);
        $response->assertStatus(200);

        $product = Product::find($product->id);
        $this->assertEquals($product->brand,  TestData::PRODUCT_BRAND);
        $this->assertEquals($product->name,  TestData::PRODUCT_NAME);
        $this->assertEquals($product->description,  TestData::PRODUCT_DESC);
        $this->assertEquals($product->specifications,  TestData::PRODUCT_SPEC);
        $this->assertEquals($product->price,  TestData::PRODUCT_PRICE);
        $this->assertEquals($product->onsale_price,  TestData::PRODUCT_ONSALE_PRICE);
        $this->assertEquals($product->stock,  TestData::PRODUCT_STOCK);
        $this->assertEquals($product->is_featured,  true);
        $this->assertEquals($product->category_id,  $category->id);
        $this->assertEquals($product->image_url,  TestData::PRODUCT_IMG);
    }

    public function test_edit_product_exists(): void
    {
        $this->seed();
        $category = Category::first();
        $product = Product::first();
        $product1 = Product::latest()->first();
        $user = User::where("is_admin", 1)->first();
        $data = [
            "name" => $product1->name,
        ];
        $response = $this->actingAs($user)->post($this::URI . $product->id, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }

    public function test_edit_product_unauth(): void
    {
        $this->seed();
        $product = Product::first();
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
        $response = $this->post($this::URI . $product->id, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }

    public function test_edit_product_not_admin(): void
    {
        $this->seed();
        $category = Category::first();
        $product = Product::first();
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
        $response = $this->actingAs($user)->post($this::URI . $product->id, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
