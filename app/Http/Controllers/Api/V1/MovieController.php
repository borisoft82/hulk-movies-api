<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helper;
use App\Filters\MovieFilter;
use App\Policies\MoviePolicy;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\MovieRepository;
use App\Http\Requests\MovieAddRequest;
use App\Services\Movie\ResponseAndLogService;
use App\Http\Requests\MovieUpdateRequest;
use App\Http\Requests\AddFavoriteMovieRequest;
use App\Models\Movie;

class MovieController extends Controller
{
    public function __construct(
        private MovieRepository $movieRepository,
        private ResponseAndLogService $responseAndLog,
        private MoviePolicy $moviePolicy
        )
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $movies = $this->movieRepository->getAll();
        return $this->responseAndLog->movieCollection($movies);
    }

    public function show(int $id): JsonResponse
    {
        $movie = $this->movieRepository->find($id);
        $authorized = $this->moviePolicy->authorize($movie->user_id);

        return (! $authorized ) 
                ? $this->responseAndLog->showMoviePolicy(Helper::loggedUserId(), $movie->user_id, $id)
                : $this->responseAndLog->movieModel($movie);
    }

    public function store(MovieAddRequest $request): JsonResponse
    {
        $response = $this->movieRepository->createMovie($request);
        return $this->responseAndLog->movieModelCreated($response);
    }

    public function update(int $id, MovieUpdateRequest $request): JsonResponse
    {
        $movie = $this->movieRepository->find($id);
        $authorized = $this->moviePolicy->authorize($movie->user_id);

        if(! $authorized) {
            return $this->responseAndLog->updateMoviePolicy(Helper::loggedUserId(), $movie->user_id, $id);
         }

        $response = $this->movieRepository->updateMovie($id, $request);
        return $this->responseAndLog->movieModelUpdated($response);
    }

    public function destroy(int $id): JsonResponse
    {
        $movie = $this->movieRepository->find($id);
        $authorized = $this->moviePolicy->authorize($movie->user_id);

        if(! $authorized) {
           return $this->responseAndLog->deleteMoviePolicy(Helper::loggedUserId(), $movie->user_id, $id);
        }

        $response = $this->movieRepository->deleteMovie($id);
        return $this->responseAndLog->movieModelDeleted($response);
    }

    public function favorite(AddFavoriteMovieRequest $request): JsonResponse 
    {   
        $user = Helper::loggedUser();
        $movieId = $request->input('movieId');
        $addFavoriteMovie = (bool) $request->input('favoriteMovie');        
        $favoriteMovieAdded = $user->favoriteMovies()->wherePivot('movie_id', $movieId)->exists();
        

        $response = match (true) {
            (! $favoriteMovieAdded && $addFavoriteMovie) => $this->addFavoriteMovie($user, $movieId),
            ($favoriteMovieAdded && !$addFavoriteMovie) => $this->removeFavoriteMovie($user, $movieId),
            default => (object) ['status' => 'Bad request. Already added or removed!'],
        };         

        return $this->responseAndLog->movieModelCreated($response);
    }

    public function filter(MovieFilter $filters) {
        $movies = Movie::filter($filters)->paginate();
        return $this->responseAndLog->movieCollection($movies);
    }

    private function addFavoriteMovie($user, $movieId) {        
        $user->favoriteMovies()->attach($movieId);
        return $this->movieRepository->findById($movieId);
    }

    private function removeFavoriteMovie($user, $movieId) {
        $user->favoriteMovies()->detach($movieId);                    
        return (object) ['message' => 'Removed successfully!'];
    }

}
