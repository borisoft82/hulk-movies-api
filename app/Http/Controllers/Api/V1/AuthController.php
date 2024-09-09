<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Services\User\ResponseAndLogService;

class AuthController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private ResponseAndLogService $responseAndLog
        )
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $token = auth()->guard('api')->attempt($credentials);

        if (!$token) {
            return $this->responseAndLog->userLoginFail();
        }

        $user = auth()->guard('api')->user();

        return $this->responseAndLog->userLoggedIn($this->userData($user, $token));
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->createUser($request);
        $token = auth()->guard("api")->login($user);

        return $this->responseAndLog->userRegistered($this->userData($user, $token));
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
        return $this->responseAndLog->userLoggedOut();
    }

    public function refresh(): JsonResponse
    {
        $data = [
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer'
            ]
        ];

        return $this->responseAndLog->userTokenRefreshed($data);
    }

    private function userData($user, $token) {
        return [
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
        ]
            ];
    }
}
