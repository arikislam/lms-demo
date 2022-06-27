<?php

namespace Smariqislam\Coupon\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Log;
use Smariqislam\Coupon\Models\Coupon;
use Smariqislam\Coupon\Services\CouponService;

class CouponController extends ApiController
{
    private $couponService;

    public function __construct()
    {
        $this->couponService = new CouponService();
        $this->middleware(config('coupon.api_middlewares'));
    }


    public function getCoupons(Request $request)
    {
        try {
            return $this->successResponse($this->couponService->getCoupons($request));
        } catch (Exception $e) {
            Log::error($e);
            return $this->errorResponse('Cannot get coupons');
        }
    }


    /**
     * @throws \Throwable
     */
    public function createCoupon(Request $request)
    {
        $validation = $this->couponService->validateCoupon($request);
        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->errors()->toArray());
        }

        try {
            $coupon = $this->couponService->createCoupon($request);
            return $this->successResponse($coupon->toArray());
        } catch (Exception $e) {
            return $this->errorResponse('Cannot create coupon');
        }
    }


    public function getCouponDetails(Coupon $coupon)
    {

    }

    public function updateCoupon(Coupon $coupon, Request $request)
    {

    }


}