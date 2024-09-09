<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends QueryFilter {

    protected $sortable = [
        'id',
        'name',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function include($value): Builder {
        $relations = explode(',', $value);

        if (count($relations) > 1) {
            return $this->builder->with($relations);
        }

        return $this->builder->with($value);
    }

    public function id($value): Builder {
        return $this->whereInArray('id', $value);
    }

    public function name($value): Builder {
        return $this->whereLikeString('name', $value);
    }

    public function createdAt($value): Builder {
        return $this->whereDateOrBetweenDates('created_at', $value);
    }

    public function updatedAt($value): Builder {
        return $this->whereDateOrBetweenDates('updated_at', $value);
    }

}