<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $units = Unit::query()
            ->select(['slug', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('sitemap', ['units' => $units])->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
