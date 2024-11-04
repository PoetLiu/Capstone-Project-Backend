<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductImageUploadTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/upload/product";
    public function test_upload_product_image(): void
    {
        $this->seed();
        $user = User::where("is_admin", 1)->first();
        $file = UploadedFile::fake()->image('product.jpg');
        
        Storage::fake("public");
        $response = $this->actingAs($user)->post($this::URI, [
            'file' => $file,
        ]);
        $response->assertStatus(200);
        $response->assertJsonPath("status", 0);

        $path = "products/". $file->hashName();
        Storage::disk()->assertExists($path);
    }

    public function test_upload_product_image_unauth(): void
    {
        $this->seed();
        $file = UploadedFile::fake()->image('product.jpg');
        
        Storage::fake("public");
        $response = $this->post($this::URI, [
            'file' => $file,
        ]);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);

        $path = "products/". $file->hashName();
        Storage::disk()->assertMissing($path);
    }

    public function test_upload_product_missing_file(): void
    {
        $this->seed();
        $user = User::where("is_admin", 1)->first();
        $file = null;
        
        Storage::fake("public");
        $response = $this->actingAs($user)->post($this::URI, [
            'file' => $file,
        ]);
        $response->assertStatus(400);
        $response->assertJsonPath("status", 1);
    }
}
