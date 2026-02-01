<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Division;
use App\Models\District;
use App\Models\Thana;

class LocationController extends Controller
{
    public function countries()
    {
        $countries = Country::select('id', 'name_en', 'name_bn', 'dial_code')->get();
        return response()->json([
            'status' => true,
            'data' => $countries
        ]);
    }

    public function divisions($country_id)
    {
        $divisions = Division::where('country_id', $country_id)
            ->select('id', 'country_id', 'name_en', 'name_bn')
            ->get();
            
        return response()->json([
            'status' => true,
            'data' => $divisions
        ]);
    }

    public function districts($division_id)
    {
        $districts = District::where('division_id', $division_id)
            ->select('id', 'division_id', 'name_en', 'name_bn')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $districts
        ]);
    }

    public function thanas($district_id)
    {
        $thanas = Thana::where('district_id', $district_id)
            ->select('id', 'district_id', 'name_en', 'name_bn')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $thanas
        ]);
    }
}
