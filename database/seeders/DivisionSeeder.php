<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\Country;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $json = file_get_contents('https://raw.githubusercontent.com/nuhil/bangladesh-geocode/master/divisions/divisions.json');
        $data = json_decode($json, true);
        $country = Country::where('name_en', 'Bangladesh')->first();

        if ($country && isset($data[2]['data'])) {
            foreach ($data[2]['data'] as $item) {
                Division::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'country_id' => $country->id,
                        'name_en' => $item['name'],
                        'name_bn' => $item['bn_name']
                    ]
                );
            }
        }
    }
}
