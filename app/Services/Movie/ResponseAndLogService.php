<?php

namespace App\Services\Movie;

use App\Constants\Movie\LogMessages;
use App\Traits\ApiResponse;
use App\Constants\Movie\ErrorMessages;
use Illuminate\Http\Response;
use App\Enums\HttpStatusMessages;
use Illuminate\Support\Facades\Log;

class ResponseAndLogService {

    use ApiResponse;

    public function movieCollection($response): object 
    {
        Log::info(LogMessages::COLLECTION_RETRIEVED);
        return $this->success(HttpStatusMessages::OK->value, $response, Response::HTTP_OK);
    }

    public function movieModel(object|int $response): object 
    {
        Log::info(LogMessages::MODEL_RETRIEVED);
        return $this->success(HttpStatusMessages::OK->value, $response, Response::HTTP_OK);
    }

    public function movieModelCreated(?object $response): object 
    {
        Log::info(LogMessages::MODEL_CREATED);
        return $this->success(HttpStatusMessages::CREATED->value, $response, Response::HTTP_CREATED);
    }

    public function movieModelUpdated(?object $response): object 
    {
        Log::info(LogMessages::MODEL_UPDATED);
        return $this->success(HttpStatusMessages::UPDATED->value, $response, Response::HTTP_OK);
    }

    public function movieModelDeleted(bool $response): object 
    {
        Log::info(LogMessages::MODEL_DELETED);
        return $this->success(HttpStatusMessages::DELETED->value, $response, Response::HTTP_NO_CONTENT);
    }

    public function showMoviePolicy(int $loggedUser, int $userId, int $movieId): object 
    {
        Log::warning(LogMessages::POLICY_SHOW_MOVIE . "User with ID: {$loggedUser} tried to see movie ID: {$movieId} whose owner is user with ID: {$userId}. ");
        return $this->error(ErrorMessages::NOT_ALLOWED_TO_SHOW, Response::HTTP_FORBIDDEN);
    }

    public function updateMoviePolicy(int $loggedUser, int $userId, int $movieId): object 
    {
        Log::warning(LogMessages::POLICY_UPDATE_MOVIE . "User with ID: {$loggedUser} tried to update movie ID: {$movieId} whose owner is user with ID: {$userId}. ");
        return $this->error(ErrorMessages::NOT_ALLOWED_TO_UPDATE, Response::HTTP_FORBIDDEN);
    }

    public function deleteMoviePolicy(int $loggedUser, int $userId, int $movieId): object 
    {
        Log::warning(LogMessages::POLICY_DELETE_MOVIE . "User with ID: {$loggedUser} tried to delete movie ID: {$movieId} whose owner is user with ID: {$userId}. ");
        return $this->error(ErrorMessages::NOT_ALLOWED_TO_DELETE, Response::HTTP_FORBIDDEN);
    }

}