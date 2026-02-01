<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Thana;

class ThanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        // Increasing memory limit for large JSON processing if needed
        ini_set('memory_limit', '512M');
        
        $json = file_get_contents('https://raw.githubusercontent.com/nuhil/bangladesh-geocode/master/upazilas/upazilas.json');
        $data = json_decode($json, true);

        if (isset($data[2]['data'])) {
            foreach ($data[2]['data'] as $item) {
                Thana::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'district_id' => $item['district_id'],
                        'name_en' => $item['name'],
                        'name_bn' => $item['bn_name']
                    ]
                );
            }
        }
    }
}
