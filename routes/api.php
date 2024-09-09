<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\UserMovieController;
use App\Http\Controllers\Api\V1\MovieController;

Route::group(['middleware' => 'cors'], function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::name('categories.')->prefix('/categories')->group(function () {
            Route::get('/', 'index')->name('get');
            Route::get('/filter', 'filter')->name('filter');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/create', 'store')->name('create');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');     
        });
    });

    Route::controller(MovieController::class)->group(function () {
        Route::name('movies.')->prefix('/movies')->group(function () {
            Route::get('/', 'index')->name('get');
            Route::get('/filter', 'filter')->name('filter');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/favorite', 'favorite')->name('favorite');
            Route::post('/create', 'store')->name('create');
            Route::put('/update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('delete');    
        });
    });

    Route::controller(UserMovieController::class)->group(function () {
        Route::get('/users/{user_id}/movies', 'filterUserMovies')->name('get-user-movies');
        Route::get('/users/favorite', 'favorite')->name('get-favorite-movies');
    });

});
