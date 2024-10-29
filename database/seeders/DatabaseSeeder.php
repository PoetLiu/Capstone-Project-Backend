<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\Data\TestData;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'username' => TestData::USER_NAME,
            "email" => TestData::EMAIL,
            'password' => Hash::make(TestData::PWD)
        ]);

        $user->createToken("MyApp");

        DB::table('provinces')->insert([
            ["name" => "Alberta", "abbr" => "AB"],
            ["name" => "British Columbia", "abbr" =>"BC"],
            ["name" => "Manitoba", "abbr" =>"MB"],
            ["name" => "New Brunswick", "abbr" =>"NB"],
            ["name" => "Newfoundland and Labrador", "abbr" =>"NL"],
            ["name" => "Northwest Territories", "abbr" =>"NT"],
            ["name" => "Nova Scotia", "abbr" =>"NS"],
            ["name" => "Nunavut", "abbr" =>"NU"],
            ["name" => "Ontario", "abbr" =>"ON"],
            ["name" => "Prince Edward Island", "abbr" =>"PE"],
            ["name" => "Quebec", "abbr" =>"QC"],
            ["name" => "Saskatchewan", "abbr" =>"SK"],
            ["name" => "Yukon", "abbr" => "YT"],
        ]);

        DB::table('categories')->insert([
            ["name" => "Fruits", "icon" => "fa-apple-whole"],
            ["name" => "Vegetables", "icon" => "fa-carrot"],
            ["name" => "Diary", "icon" => "fa-cheese"],
            ["name" => "Beverages", "icon" => "fa-coffee"],
            ["name" => "Snacks", "icon" => "fa-cookie"],
            ["name" => "Bakery", "icon" => "fa-bread-slice"],
            ["name" => "Grains", "icon" => "fa-seedling"],
            ["name" => "Meat", "icon" => "fa-drumstick-bite"],
            ["name" => "Seafood", "icon" => "fa-fish"],
            ["name" => "Frozen Foods", "icon" => "fa-ice-cream"],
        ]);

        Product::factory()->count(10)->create();
    }
}
