<?php


namespace Smariqislam\Coupon\Http\Controllers;

use App\Enums\ResponseStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{


    public function validationErrorResponse($messages = [], $data = [], $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        return response()->json([
            'status'  => ResponseStatusEnum::FAILED,
            'data'    => $data,
            'message' => $messages,
        ], $responseCode);
    }

    public function successResponse($data = [], $message = null, $responseCode = Response::HTTP_OK): JsonResponse
    {

        return response()->json([
            'status'  => ResponseStatusEnum::SUCCESS,
            'data'    => $data,
            'message' => $message,
        ], $responseCode);
    }

    public function errorResponse($message = null, $data = [], $responseCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status'  => ResponseStatusEnum::FAILED,
            'data'    => $data,
            'message' => $message,
        ], $responseCode);
    }


}