<?php

use Smariqislam\Coupon\Http\Controllers\CouponController;

Route::prefix(config('coupon.api_route_base_prefix'))->group(function () {
    Route::controller(CouponController::class)
        ->prefix('coupons')->group(function () {
            Route::get('/', 'getCoupons');
            Route::post('/create', 'createCoupon');
            Route::post('/{coupon}/update', 'updateCoupon');
            Route::get('/{coupon}/details', 'getCouponDetails');
            Route::get('/search-parameters', 'getSearchParameters');
        });
});
