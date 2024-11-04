<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    const URI = "/api/upload/avatar";
    public function test_upload_avatar(): void
    {
        $this->seed();
        $user = User::first();
        $file = UploadedFile::fake()->image('avatar.jpg');
        
        Storage::fake("public");
        $response = $this->actingAs($user)->post($this::URI, [
            'file' => $file,
        ]);
        $response->assertStatus(200);
        $response->assertJsonPath("status", 0);

        $path = "avatars/". $file->hashName();
        $this->assertEquals($user->photoUrl, $path);
        Storage::disk()->assertExists($path);
    }
}
