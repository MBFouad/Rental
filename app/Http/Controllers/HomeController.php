<?php

namespace App\Http\Controllers;

use App\Models\Unit;

class HomeController extends Controller
{
    public function index()
    {
        $featuredUnits = Unit::with(['rentalDetail', 'saleDetail', 'constructionDetail', 'city', 'unitArea'])
            ->featured()
            ->available()
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredUnits'));
    }

    public function setLocale(string $locale)
    {
        if (in_array($locale, ['ar', 'en'])) {
            session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
