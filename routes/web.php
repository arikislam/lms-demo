<?php

use Illuminate\Support\Facades\Route;
use Smariqislam\Coupon\Facades\ApplyCoupon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    Cache::get('data');
    dd('I am working.');
    return redirect()->to('http://app.ariq.dev');

})->name('login');
