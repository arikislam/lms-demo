<?php

namespace Smariqislam\Coupon\Classes;

use Smariqislam\Coupon\Services\CouponService;

class Coupon
{
    public function getCouponByCode($couponCode)
    {
        return app(CouponService::class)->getCouponByCode($couponCode);

    }


    public function applyCouponToProduct($coupon, $productId)
    {
        return app(CouponService::class)->checkValidity($coupon, $productId);
    }

    public function details($couponCode): array
    {
        return [];
    }

    public function couponAppliedOnLabel($data)
    {

        return data_get(collect($this->couponAppliedOnValues())->where('value', $data)->first(), 'label', 'N/A');
    }

    public function discountTypeLabel($data)
    {
        return data_get(collect($this->discountTypeValues())->where('value', $data)->first(), 'label', 'N/A');

    }


    public function couponAppliedOnValues(): array
    {
        return app(CouponService::class)->getCouponAppliedOnValues();

    }

    public function discountTypeValues(): array
    {
        return app(CouponService::class)->getCouponDiscountTypeValues();
    }

    public function typePercentage($data)
    {
        return data_get(collect($this->discountTypeValues())->where('value', $data)->first(), 'key', 'N/A') === 'percentage';

    }
}