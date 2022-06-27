<?php

namespace App\Http\Controllers\ApiV1;

use App\Models\Course;
use App\Services\CourseService;
use App\Transformers\CourseTransformer;
use Exception;
use Illuminate\Http\Request;
use Log;

class CourseController extends ApiController
{
    private CourseService $courseService;

    public function __construct()
    {
        $this->courseService = new CourseService();
    }

    public function getCourses(Request $request)
    {
        try {
            return $this->successResponse(app(CourseTransformer::class)->transformCourses($this->courseService->getCourses($request)));
        } catch (Exception $e) {
            Log::error($e);
            return $this->errorResponse('Cannot get courses.');
        }

    }

    public function getCourseDetails($courseId)
    {
        if (blank($course = Course::find($courseId))) {
            return $this->errorResponse('Course not found');
        }

        try {
            return $this->successResponse(app(CourseTransformer::class)->transformCourse($course));
        } catch (Exception $e) {
            Log::error($e);
            return $this->errorResponse('Cannot get courses.');
        }
    }

    public function applyCoupon($courseId, Request $request)
    {
        if (blank($course = Course::find($courseId))) {
            return $this->errorResponse('Course not found');
        }

        [$validCoupon, $responseData, $errorMessage] = $this->courseService->validateAndApplyCoupon($request, $course);

        if ($validCoupon) {
            return $this->successResponse($responseData, 'Coupon applied successfully');
        } else {
            return $this->errorResponse($errorMessage);
        }

    }

}