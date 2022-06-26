<?php

namespace Smariqislam\Coupon\Http\Controllers;

use Smariqislam\Coupon\Models\Coupon;

class CouponController extends ApiController
{
    public function getCoupons()
    {
        $class= config('coupon.category_model');
        $category = new $class;

        $coupon = Coupon::with('products')->find(1);
        dd($coupon->toArray());
    }
}