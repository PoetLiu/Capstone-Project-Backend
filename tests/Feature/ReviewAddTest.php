<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Data\TestData;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

use function PHPUnit\Framework\assertEquals;

class ReviewAddTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/review/";
    public function test_add_review(): void
    {
        $this->seed();
        $user = User::where("is_admin", 0)->first();
        $product = Product::first();
        $data = [
            "product_id" => $product->id,
            "title" => TestData::REVIEW_TITLE,
            "content" => TestData::REVIEW_CONTENT,
            "stars" => TestData::REVIEW_STARS,
        ];
        $response = $this->actingAs($user)->post($this::URI, $data);
        $response->assertStatus(200);

        $review = Review::first();
        $this->assertEquals($review->product_id,  $product->id);
        $this->assertEquals($review->title,  TestData::REVIEW_TITLE);
        $this->assertEquals($review->content,  TestData::REVIEW_CONTENT);
        $this->assertEquals($review->stars,  TestData::REVIEW_STARS);
    }

    public function test_add_review_missing_params(): void
    {
        $this->seed();
        $user = User::where("is_admin", 0)->first();
        $data = [
        ];
        $response = $this->actingAs($user)->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }

    public function test_add_review_unauth(): void
    {
        $this->seed();
        $product = Product::first();
        $data = [
            "product_id" => $product->id,
            "title" => TestData::REVIEW_TITLE,
            "content" => TestData::REVIEW_CONTENT,
            "stars" => TestData::REVIEW_STARS,
        ];
        $response = $this->post($this::URI, $data);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
