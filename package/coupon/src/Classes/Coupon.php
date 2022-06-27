<?php

namespace Smariqislam\Coupon\Classes;

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

}