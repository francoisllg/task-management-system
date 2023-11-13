<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\User\UserLoginRequest;
use Src\User\Application\UseCase\Authenticate\UserAuthenticator;

class AuthUserController extends ApiController
{

    public function __construct(
        private UserAuthenticator $userAuthenticator,
    ) {
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $userAuthenticated = $this->userAuthenticator->handle($validatedData['email'], $validatedData['password']);
            return $this->successResponse($userAuthenticated, 'User logged successfully', Response::HTTP_OK);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
