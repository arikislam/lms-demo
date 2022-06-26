<?php

use Smariqislam\Coupon\Http\Controllers\CouponController;
use Smariqislam\Coupon\Http\Controllers\CourseController;

Route::prefix('api')->group(function (){
    Route::middleware('auth:sanctum')->controller(CouponController::class)->prefix('coupons')->group(function(){
       Route::get('/', 'getCoupons');
       Route::post('/create', 'getCoupons');
       Route::get('/{coupon}/details', 'getCoupons');
       Route::post('/coupon/{coupon}/update', 'getCoupons');
    });
});
