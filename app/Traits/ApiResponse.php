<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse {

    protected function success(string $message, $data = [], int $statusCode = 200): JsonResponse {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function error($errors = [], int $statusCode = null): JsonResponse {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode
            ], $statusCode);
        }

        return response()->json([
            'errors' => $errors
        ], $statusCode);
    }

    protected function exceptionInfo(int $statusCode, string $message, string $source = null): array {
        return [
            'status' => $statusCode,
            'message' => $message,
            'source' => $source
        ];
    }
    
}
    
    