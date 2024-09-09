<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\CoreDefinitions;
use App\Models\Movie;
use App\Helpers\Helper;
use App\Filters\MovieFilter;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use App\Services\Movie\ResponseAndLogService;

class UserMovieController extends Controller {

    public function __construct
    (
        private UserRepository $userRepository,
        private ResponseAndLogService $responseAndLog
       
    ){
        $this->middleware('auth:api');
    }

    public function favorite(): JsonResponse 
    {
        $userId = Helper::loggedUserId();
        $cacheKey = "user_fav_movies_{$userId}";
        $response = $this->userRepository->getFavorite($userId);
        
        $response = Cache::remember($cacheKey, CoreDefinitions::FAVORITE_CACHE_TIME, function() use ($response) {
            return $response;
        });

        return $this->responseAndLog->movieCollection($response);
     }

     public function filterUserMovies(int $user_id, MovieFilter $filters) {
        return Movie::where('user_id', $user_id)->filter($filters)->paginate();
     }
}

