<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;

class LocationController extends Controller
{
    public function cities()
    {
        return City::active()
            ->ordered()
            ->get(['id', 'name', 'slug']);
    }

    public function areas(City $city)
    {
        return $city->areas()
            ->active()
            ->ordered()
            ->get(['id', 'name', 'slug']);
    }
}
