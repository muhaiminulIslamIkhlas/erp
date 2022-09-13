<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public function success($data, $message = "Successful", $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'error' => null
        ], $status);
    }

    protected function failure($error, $message = "Something Went Wrong",  $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $error,
        ], $status);
    }
}
