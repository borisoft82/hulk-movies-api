<?php

namespace App\Services\User;

use App\Constants\User\LogMessages;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Enums\HttpStatusMessages;
use Illuminate\Support\Facades\Log;

class ResponseAndLogService {

    use ApiResponse;

    public function userRegistered($response): object 
    {
        Log::info(LogMessages::USER_REGISTERED);
        return $this->success(HttpStatusMessages::CREATED->value, $response, Response::HTTP_CREATED);
    }

    public function userLoggedIn($response): object 
    {
        Log::info(LogMessages::USER_LOGGED_IN);
        return $this->success(HttpStatusMessages::LOGGED_IN->value, $response, Response::HTTP_OK);
    }

    public function userLoginFail(): object 
    {
        Log::info(LogMessages::USER_FAIL_LOGIN);
        return $this->error(HttpStatusMessages::UNAUTHORIZED->value, Response::HTTP_UNAUTHORIZED);
    }

    public function userLoggedOut(): object 
    {
        Log::info(LogMessages::USER_LOGGED_OUT);
        return $this->success(HttpStatusMessages::LOGGED_OUT->value, [], Response::HTTP_OK);
    }

    public function userTokenRefreshed($response): object 
    {
        Log::info(LogMessages::USER_TOKEN_REFRESHED);
        return $this->success(HttpStatusMessages::TOKEN_REFRESHED->value, $response, Response::HTTP_OK);
    }

}