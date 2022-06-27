<?php

namespace Smariqislam\Coupon\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Log;
use Smariqislam\Coupon\Models\Coupon;
use Smariqislam\Coupon\Services\CouponService;
use Smariqislam\Coupon\Transformers\TransformData;

class CouponController extends ApiController
{
    private CouponService $couponService;

    public function __construct()
    {
        $this->couponService = new CouponService();
        $this->middleware(config('coupon.api_middlewares'));
    }


    public function getCoupons(Request $request)
    {
        try {
            return $this->successResponse(app(TransformData::class)->transformCoupons($this->couponService->getCoupons($request)));
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
            return $this->successResponse(app(TransformData::class)->transformCoupon($coupon));
        } catch (Exception $e) {
            return $this->errorResponse('Cannot create coupon');
        }
    }


    public function getCouponDetails($couponId)
    {
        if (blank($coupon = Coupon::find($couponId))) {
            return $this->errorResponse('Coupon not found');
        }

        try {
            return $this->successResponse(app(TransformData::class)->transformCoupon($coupon));
        } catch (Exception $e) {
            return $this->errorResponse('Cannot create coupon');
        }
    }

    public function updateCoupon($couponId, Request $request)
    {
        if (blank($coupon = Coupon::find($couponId))) {
            return $this->errorResponse('Coupon not found');
        }

        $validation = $this->couponService->validateCoupon($request, $coupon->id);
        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->errors()->toArray());
        }

        try {
            $coupon = $this->couponService->updateCoupon($request, $coupon);
            return $this->successResponse(app(TransformData::class)->transformCoupon($coupon));
        } catch (Exception $e) {
            return $this->errorResponse('Cannot create coupon');
        }
    }

    public function getSearchParameters()
    {
        return $this->successResponse($this->couponService->getSearchParams());
    }


}