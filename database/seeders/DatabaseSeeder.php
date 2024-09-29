<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

require_once __DIR__ . "/../../tests/Data/Common.php";

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => USER_NAME,
            'email' => EMAIL,
            'password' => Hash::make(PWD)
        ]);
    }
}
