<?php

use App\Http\Controllers\Api\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/cities', [LocationController::class, 'cities']);
Route::get('/cities/{city}/areas', [LocationController::class, 'areas']);
