<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Filament\Resources\ServerResource\Api\ServerApiService;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// مسیر برای دریافت اطلاعات کاربر احراز هویت‌شده
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
