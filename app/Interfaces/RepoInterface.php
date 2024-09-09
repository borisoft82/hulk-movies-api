<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface RepoInterface {

    public function create(array $attributes): Model;

    public function fetchAll(): Collection;

    public function findById(int $id): Model;

   public function update(array $params, int $id): Model;

   public function delete(int $id): bool;

}