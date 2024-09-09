<?php

namespace App\Repositories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MovieRepository extends BaseRepository
{
    protected $model;

    public function __construct(Movie $model)
    {
        parent::__construct($model);
    }

    public function createMovie(Request $request): Model
    {
        return $this->create($request->validated());
    }

    public function getAll(): Collection
    {
        return $this->fetchAll();
    }

    public function find($id): Model
    {
        return $this->findById($id);
    }

    public function updateMovie(int $id, Request $request)
    {
        return $this->update($request->validated(), $id);
    }

    public function deleteMovie(int $id): bool
    {
        return $this->delete($id);
    }

}
