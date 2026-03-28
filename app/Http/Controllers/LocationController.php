<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Subdistrict;

class LocationController extends Controller
{
    public function getDistricts($province_id)
    {
        return District::where('province_id', $province_id)
            ->orderBy('name_th')
            ->get(['id', 'name_th']);
    }

    public function getSubdistricts($district_id)
    {
        return Subdistrict::where('district_id', $district_id)
            ->orderBy('name_th')
            ->get(['id', 'name_th']);
    }
}