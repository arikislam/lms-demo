<?php

use App\Http\Controllers\ApiV1\AuthController;
use App\Http\Controllers\ApiV1\CourseController;
use App\Http\Controllers\ApiV1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->name('api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('user', [UserController::class, 'getUser'])->name('user.info');
});

Route::controller(AuthController::class)->name('api.')->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
});

Route::controller(CourseController::class)->prefix('courses')->name('api.courses')->group(function () {
    Route::get('/', 'getCourses')->name('get');
    Route::get('/{course}/details', 'getCourseDetails')->name('details');
    Route::post('/{course}/apply-coupon', 'applyCoupon')->name('apply-coupon');
});
