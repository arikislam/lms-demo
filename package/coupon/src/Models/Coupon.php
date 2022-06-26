<?php

namespace Smariqislam\Coupon\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(config('coupon.product_model'), 'coupon_product', 'coupon_id', 'product_id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(config('coupon.category_model'), 'product_category_id');
    }
}