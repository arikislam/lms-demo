<?php

namespace Smariqislam\Coupon\Enums;


enum CouponDiscountType: int
{
    case PERCENTAGE = 1;
    case FIXED_AMOUNT = 2;
}