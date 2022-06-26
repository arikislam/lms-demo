<?php

namespace Smariqislam\Coupon\Models;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
      'label',
      'code',
      'coupon_applied_on',
      'product_category_id',
      'discount_type',
      'discount_amount',
      'expire_date',
      'status'
    ];
}