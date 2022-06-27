<?php

namespace Smariqislam\Coupon\Services;

use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;
use Smariqislam\Coupon\Models\Coupon;
use Validator;

class CouponService
{
    public const  COUPON_DISCOUNT_FIXED_PRICE = [
        'value' => 1,
        'label' => 'Fixed Price',
        'key'   => 'fixed_price',
    ];
    public const  COUPON_DISCOUNT_PERCENTAGE = [
        'value' => 2,
        'label' => 'Percentage',
        'key'   => 'percentage',
    ];
    public const  COUPON_APPLIED_ON_PRODUCTS = [
        'value' => 1,
        'label' => 'Products',
        'key'   => 'products',
    ];
    public const  COUPON_APPLIED_ON_PRODUCT_CATEGORIES = [
        'value' => 2,
        'label' => 'Product Categories',
        'key'   => 'product_categories',
    ];


    public function getProducts()
    {

    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function createCoupon(Request $request)
    {
        [$couponData, $productData] = $this->prepareCouponData($request);

        DB::beginTransaction();
        try {
            $coupon = Coupon::create($couponData);
            if (!blank($productData)) {
               $coupon->products()->sync($productData);
            }
            DB::commit();
            return $coupon;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            dd($e);
            throw new Exception('Coupon cannot be created.');

        }

    }

    public function updateCoupon()
    {

    }


    public function getCouponDetails()
    {

    }

    public function getCoupons()
    {

        return [];
    }

    public function validateCoupon(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'label'               => 'required|max:250',
            'code'                => 'required|regex:/(^([a-zA-Z0-9\-_]+)?$)/u|unique:coupons,code',
            'coupon_applied_on'   => ['required', Rule::in(array_values(array_column($this->getCouponAppliedOnValues(), 'value')))],
            'discount_type'       => ['required', Rule::in(array_values(array_column($this->getCouponDiscountTypeValues(), 'value')))],
            'product_category_id' => Rule::requiredIf($request->get('coupon_applied_on') == data_get(self::COUPON_APPLIED_ON_PRODUCT_CATEGORIES, 'value')),
            'discount_amount'     => 'required|numeric',
            'products'            => ['array', Rule::requiredIf($request->get('coupon_applied_on') == data_get(self::COUPON_APPLIED_ON_PRODUCTS, 'value'))],
            'expire_date'         => 'required|date|after:today',
            'status'              => 'required|boolean',
        ], [
            'code.regex'        => 'Please provide a valid coupon code (With no spaces)',
            'products.required' => 'Please select some products',

        ]);
    }

    private function getCouponAppliedOnValues(): array
    {
        return [self::COUPON_APPLIED_ON_PRODUCTS, self::COUPON_APPLIED_ON_PRODUCT_CATEGORIES];
    }

    private function getCouponDiscountTypeValues(): array
    {
        return [self::COUPON_DISCOUNT_PERCENTAGE, self::COUPON_DISCOUNT_FIXED_PRICE];
    }


//$class= config('coupon.category_model');
//$category = new $class;
//
//$coupon = Coupon::with('products')->find(1);
//dd($coupon->toArray());
    private function prepareCouponData(Request $request): array
    {
        $couponData        = $request->only('label', 'code', 'coupon_applied_on', 'discount_type', 'discount_amount', 'expire_date', 'status');
        $productCategoryId = $couponData['product_category_id'] = ($request->get('coupon_applied_on') === data_get(self::COUPON_APPLIED_ON_PRODUCT_CATEGORIES, 'value')) ? $request->get('product_category_id'): null;
        $products          = $productCategoryId ? [] : $request->get('products');

        return [$couponData, $products];
    }
}