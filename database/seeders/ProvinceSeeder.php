<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

 
class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

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
    }
}
