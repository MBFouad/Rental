<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

// Set locale
Route::get('/locale/{locale}', [HomeController::class, 'setLocale'])->name('locale.set');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Units
Route::get('/units', [UnitController::class, 'index'])->name('units.index');
Route::get('/units/rental', [UnitController::class, 'rental'])->name('units.rental');
Route::get('/units/sale', [UnitController::class, 'sale'])->name('units.sale');
Route::get('/units/construction', [UnitController::class, 'construction'])->name('units.construction');
Route::get('/units/{slug}', [UnitController::class, 'show'])->name('units.show');

// Inquiries
Route::post('/units/{unit}/inquiry', [InquiryController::class, 'store'])->name('units.inquiry');
