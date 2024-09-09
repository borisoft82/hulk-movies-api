<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavMovieUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fav_movie_user')->insert(
            [
                [
                    'user_id' => 4,
                    'movie_id' => 25,
                ],
                [
                    'user_id' => 4,
                    'movie_id' => 26,
                ],
                [
                    'user_id' => 4,
                    'movie_id' => 27,
                ],
            ]);
    }
}
