<?php

declare(strict_types=1);

namespace App\Traits\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

trait ApiResponserTrait
{

    protected function successResponse(mixed $data, mixed $message = 'Success', int $code = 200): JsonResponse
    {
        return new JsonResponse([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse(mixed $message = '', int $code = 400): JsonResponse
    {
        if($code === 0){$code = 500;}
        return new JsonResponse([
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
