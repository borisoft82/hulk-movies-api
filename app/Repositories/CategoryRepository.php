<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CategoryRepository extends BaseRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function createCategory(Request $request): Model
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

    public function updateCategory(int $id, Request $request)
    {
        return $this->update($request->validated(), $id);
    }

    public function deleteCategory(int $id): bool
    {
        return $this->delete($id);
    }

}
