<?php

namespace Smariqislam\Coupon\Classes;

use Smariqislam\Coupon\Services\CouponService;

class Coupon
{
    public function checkValidity($couponCode): bool
    {
        return true;
    }

    public function applicable($couponCode, $productId): bool
    {
        return true;
    }


    public function finalPrice($couponCode, $productId): float
    {
        return 100.0;
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
}