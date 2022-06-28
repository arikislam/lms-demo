<?php

namespace Smariqislam\Coupon\Services;

use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;
use Smariqislam\Coupon\Models\Coupon;
use Smariqislam\Coupon\Transformers\TransformData;
use Validator;

class CouponService
{
    public const  COUPON_DISCOUNT_FIXED_PRICE = 1;
    public const  COUPON_DISCOUNT_PERCENTAGE = 2;
    public const  COUPON_APPLIED_ON_PRODUCTS = 1;
    public const  COUPON_APPLIED_ON_PRODUCT_CATEGORIES = 2;


    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function createCoupon(Request $request): Coupon
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
            throw new Exception('Coupon cannot be created.');

        }

    }

    public function updateCoupon(Request $request, $coupon)
    {
        $coupon->load('products');
        [$couponData, $productData] = $this->prepareCouponData($request);


        DB::beginTransaction();
        try {
            $coupon = $coupon->fill($couponData);
            $coupon->save();
            if (!blank($coupon->product_category_id) && !blank($coupon->products)) {
                $coupon->products()->detach();
            }

            if (!blank($productData)) {
                $coupon->products()->sync($productData);
            }

            DB::commit();
            return $coupon;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw new Exception('Coupon cannot be created.');

        }

    }


    public function getCoupons($request)
    {
        $keyword   = $request->get('keyword');
        $type      = $request->get('type');
        $appliedOn = $request->get('applied_on');

        $query = Coupon::query()->with('productCategory');
        if (!blank($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('label', 'like', '%' . $keyword . '%')->orWhere('code', 'like', '%', $keyword . '%');
            });
        }

        if (!blank($type) && in_array($type, $this->getCouponDiscountTypeValues())) {
            $query->where('discount_type', $type);
        }

        if (!blank($appliedOn) && in_array($appliedOn, $this->getCouponAppliedOnValues())) {
            $query->where('coupon_applied_on', $appliedOn);
        }


        return $query->paginate(config('coupon.per_page_data', 20));
    }

    public function validateCoupon(Request $request, $id = null): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'label'               => 'required|max:250',
            'code'                => 'required|regex:/(^([a-zA-Z0-9\-_]+)?$)/u|unique:coupons,code,' . $id,
            'coupon_applied_on'   => ['required', Rule::in($this->getCouponAppliedOnValues())],
            'discount_type'       => ['required', Rule::in($this->getCouponDiscountTypeValues())],
            'product_category_id' => Rule::requiredIf($request->get('coupon_applied_on') == self::COUPON_APPLIED_ON_PRODUCT_CATEGORIES),
            'discount_amount'     => 'required|numeric',
            'products'            => ['array', Rule::requiredIf($request->get('coupon_applied_on') == self::COUPON_APPLIED_ON_PRODUCTS)],
            'expire_date'         => 'required|date|after:today',
            'status'              => 'required|boolean',
        ], [
            'code.regex'        => 'Please provide a valid coupon code (With no spaces)',
            'products.required' => 'Please select some products',

        ]);
    }

    public function getCouponAppliedOnValues(): array
    {
        return [self::COUPON_APPLIED_ON_PRODUCTS, self::COUPON_APPLIED_ON_PRODUCT_CATEGORIES];
    }

    public function getCouponDiscountTypeValues(): array
    {
        return [self::COUPON_DISCOUNT_PERCENTAGE, self::COUPON_DISCOUNT_FIXED_PRICE];
    }

    private function prepareCouponData(Request $request): array
    {

        $couponData        = $request->only('label', 'code', 'coupon_applied_on', 'discount_type', 'discount_amount', 'expire_date', 'status');
        $productCategoryId = $couponData['product_category_id'] = ($request->get('coupon_applied_on') == self::COUPON_APPLIED_ON_PRODUCT_CATEGORIES) ? $request->get('product_category_id') : null;
        $products          = !blank($productCategoryId) ? [] : $request->get('products');

        return [$couponData, $products];
    }

    public function getSearchParams()
    {
        return [
            'coupon_applied_on'     => $this->getCouponAppliedOn(),
            'coupon_discount_types' => $this->getCouponDiscountTypes(),
        ];
    }

    public function getCouponByCode($code): ?Coupon
    {
        return Coupon::findByCode($code);
    }

    public function checkValidity(Coupon $coupon, $productId)
    {
        $class = config('coupon.product_model');

        $model = new $class;

        if (blank($product = $model->find($productId))) {
            return false;
        }

        if ($coupon->product_category_id && data_get($product, config('coupon.product_category_column')) != $coupon->product_category_id) {
            return false;
        }

        if (blank($coupon->products->where(config('coupon.product_primary_key'), $productId)->first())) {
            return false;
        }

        return app(TransformData::class)->calculateDiscount($coupon, $product);

    }

    public function getCouponAppliedOn(): array
    {
        return [
            [
                'value' => self::COUPON_APPLIED_ON_PRODUCTS,
                'label' => 'Products',
                'key'   => 'products',
            ],

            [
                'value' => self::COUPON_APPLIED_ON_PRODUCT_CATEGORIES,
                'label' => 'Product categories',
                'key'   => 'product-categories',
            ],

        ];
    }

    public function getCouponDiscountTypes(): array
    {
        return [
            [
                'value' => self::COUPON_DISCOUNT_FIXED_PRICE,
                'label' => 'Fixed Price',
                'key'   => 'Percentage',
            ],

            [
                'value' => self::COUPON_DISCOUNT_PERCENTAGE,
                'label' => 'Percentage',
                'key'   => 'percentage',
            ],

        ];
    }


}