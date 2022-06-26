<?php

namespace Smariqislam\Coupon\Http\Controllers;

class CouponController extends ApiController
{
    public function getCoupons()
    {
        $class= config('coupon.category_model');

        $category = new $class;
        dd($category);
    }
}