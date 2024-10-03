<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert([
            ["name" => "Fruits"],
            ["name" => "Vegetables"],
            ["name" => "Diary"],
            ["name" => "Beverages"],
            ["name" => "Snacks"],
            ["name" => "Bakery"],
            ["name" => "Grains"],
            ["name" => "Meat"],
            ["name" => "Seafood"],
            ["name" => "Frozen Foods"],
        ]);
    }
}
