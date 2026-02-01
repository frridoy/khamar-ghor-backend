<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        Country::updateOrCreate(
            ['name_en' => 'Bangladesh'],
            ['name_bn' => 'বাংলাদেশ', 'dial_code' => '+880']
        );
    }
}
