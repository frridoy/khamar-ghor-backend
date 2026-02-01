<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $json = file_get_contents('https://raw.githubusercontent.com/nuhil/bangladesh-geocode/master/districts/districts.json');
        $data = json_decode($json, true);

        if (isset($data[2]['data'])) {
            foreach ($data[2]['data'] as $item) {
                District::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'division_id' => $item['division_id'],
                        'name_en' => $item['name'],
                        'name_bn' => $item['bn_name']
                    ]
                );
            }
        }
    }
}
