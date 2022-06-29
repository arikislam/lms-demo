<?php

namespace App\Services;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Smariqislam\Coupon\Facades\Coupon;

class CourseService
{

    public function getCourses(Request $request)
    {
        $keyword = $request->get('keyword');

        $query = Course::query()->with('category');

        if (!blank($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%')
                ->orWhereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
        }

        return $query->latest()->paginate($request->get('rows', 20));

    }


    public function validateAndApplyCoupon(Request $request, Course $course): array
    {
        if (blank($couponCode = $request->get('coupon_code'))) {
            return [false, [], 'Coupon code is required'];
        }

        if (blank($coupon = Coupon::getCouponByCode($couponCode))) {
            return [false, [], 'Invalid Coupon Code'];
        }

        if ($coupon->expire_date < Carbon::now()) {
            return [false, [], 'Coupon is already expired.'];
        }

        $validProduct = Coupon::applyCouponToProduct($coupon, $course->id);

        if (!$validProduct) {
            return [false, [], 'Coupon is not valid for this product'];
        } else {
            return [true, $validProduct, 'Coupon is already expired.'];
        }
    }

    public function searchCourses(Request $request): array
    {
        $keyword = $request->get('keyword');

        $query = Course::query()->with('category');

        if (!blank($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%')
                ->orWhereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
        }


        return $query->take(10)->get()->map(function ($item) {
            return [
                'value' => data_get($item, 'id'),
                'label' => data_get($item, 'title'),
            ];
        })->toArray();

    }
}