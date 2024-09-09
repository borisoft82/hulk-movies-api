<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserRepository extends BaseRepository
{
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function createUser(Request $request): Model
    {
        return $this->create($request->validated());
    }

    public function getAll(): Collection
    {
        return $this->fetchAll();
    }

    public function find(int $id): Model
    {
        return $this->findById($id);
    }

    public function updateUser(int $id, Request $request): Model
    {
        return $this->update($request->validated(), $id);
    }

    public function deleteUser(int $id): bool
    {
        return $this->delete($id);
    }

    public function getFavorite(int $id): Collection
    {
        return $this->findById($id)
                    ->favoriteMovies()
                    ->get();
    }

}
