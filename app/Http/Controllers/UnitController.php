<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with(['rentalDetail', 'saleDetail', 'constructionDetail', 'city', 'unitArea'])
            ->available();

        // Type filter
        if ($request->filled('type') && in_array($request->type, ['rental', 'sale', 'under_construction'])) {
            $query->where('type', $request->type);
        }

        // City filter
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Area filter
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        // Search filter (title and description)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        // Price range filter
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $priceMin = $request->price_min;
            $priceMax = $request->price_max;

            $query->where(function ($q) use ($priceMin, $priceMax) {
                // Rental units - monthly rent
                $q->whereHas('rentalDetail', function ($sub) use ($priceMin, $priceMax) {
                    if ($priceMin) {
                        $sub->where('monthly_rent', '>=', $priceMin);
                    }
                    if ($priceMax) {
                        $sub->where('monthly_rent', '<=', $priceMax);
                    }
                })
                // Sale units - sale price
                ->orWhereHas('saleDetail', function ($sub) use ($priceMin, $priceMax) {
                    if ($priceMin) {
                        $sub->where('sale_price', '>=', $priceMin);
                    }
                    if ($priceMax) {
                        $sub->where('sale_price', '<=', $priceMax);
                    }
                })
                // Construction units - total price
                ->orWhereHas('constructionDetail', function ($sub) use ($priceMin, $priceMax) {
                    if ($priceMin) {
                        $sub->where('total_price', '>=', $priceMin);
                    }
                    if ($priceMax) {
                        $sub->where('total_price', '<=', $priceMax);
                    }
                });
            });
        }

        // Bedrooms filter
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Bathrooms filter
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        $units = $query->latest()->paginate(12)->withQueryString();
        $cities = City::active()->ordered()->get();

        return view('units.index', [
            'units' => $units,
            'cities' => $cities,
            'filters' => $request->only(['type', 'city_id', 'area_id', 'search', 'price_min', 'price_max', 'bedrooms', 'bathrooms']),
        ]);
    }

    public function show(string $slug)
    {
        $unit = Unit::with([
            'rentalDetail',
            'saleDetail',
            'constructionDetail.paymentPlans',
            'city',
            'unitArea',
        ])->where('slug', $slug)->firstOrFail();

        return view('units.show', compact('unit'));
    }

    public function rental(Request $request)
    {
        $request->merge(['type' => 'rental']);
        return $this->index($request)->with([
            'pageTitle' => __('Rental Units'),
        ]);
    }

    public function sale(Request $request)
    {
        $request->merge(['type' => 'sale']);
        return $this->index($request)->with([
            'pageTitle' => __('Sale Units'),
        ]);
    }

    public function construction(Request $request)
    {
        $request->merge(['type' => 'under_construction']);
        return $this->index($request)->with([
            'pageTitle' => __('Under Construction Units'),
        ]);
    }
}
