<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FavMovieUser extends Pivot
{
    use HasFactory;

    protected $fillable = ['user_id', 'movie_id'];

    protected $table = 'fav_movie_user';
}
