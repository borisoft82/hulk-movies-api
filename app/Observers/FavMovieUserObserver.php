<?php

namespace App\Observers;

use App\Models\FavMovieUser;
use Illuminate\Support\Facades\Cache;

class FavMovieUserObserver
{
    public function created(FavMovieUser $favMovieUser): void
    {
        $this->refreshCache($favMovieUser->user_id);
    }

    public function deleted(FavMovieUser $favMovieUser): void
    {
        $this->refreshCache($favMovieUser->user_id);
    }

    public function updated(FavMovieUser $favMovieUser): void
    {
        $this->refreshCache($favMovieUser->user_id);
    }

    private function refreshCache($userId): void
    {        
        $cacheKey = "user_fav_movies_{$userId}";
        Cache::forget($cacheKey);
    }
}
