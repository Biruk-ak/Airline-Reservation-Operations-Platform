<?php
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return response()->json([
        'app' => 'Airline Reservation & Operations Platform',
        'docs' => '/api/health',
    ]);
});
