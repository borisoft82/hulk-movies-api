<?php

namespace App\Services\Category;

use App\Constants\Category\LogMessages;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Enums\HttpStatusMessages;
use Illuminate\Support\Facades\Log;

class ResponseAndLogService {

    use ApiResponse;

    public function categoryCollection($response): object 
    {
        Log::info(LogMessages::COLLECTION_RETRIEVED);
        return $this->success(HttpStatusMessages::OK->value, $response, Response::HTTP_OK);
    }

    public function categoryModel(object|int $response): object 
    {
        Log::info(LogMessages::MODEL_RETRIEVED);
        return $this->success(HttpStatusMessages::OK->value, $response, Response::HTTP_OK);
    }

    public function categoryModelCreated(?object $response): object 
    {
        Log::info(LogMessages::MODEL_CREATED);
        return $this->success(HttpStatusMessages::CREATED->value, $response, Response::HTTP_CREATED);
    }

    public function categoryModelUpdated(?object $response): object 
    {
        Log::info(LogMessages::MODEL_UPDATED);
        return $this->success(HttpStatusMessages::UPDATED->value, $response, Response::HTTP_OK);
    }

    public function categoryModelDeleted(bool $response): object 
    {
        Log::info(LogMessages::MODEL_DELETED);
        return $this->success(HttpStatusMessages::DELETED->value, $response, Response::HTTP_NO_CONTENT);
    }

}