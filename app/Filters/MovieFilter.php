<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class MovieFilter extends QueryFilter {

    protected $sortable = [
        'id',
        'title',
        'storyline',
        'director',
        'writer',
        'cast',
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

    public function title($value): Builder {
        return $this->whereLikeString('title', $value);
    }

    public function storyline($value): Builder {
        return $this->whereLikeString('storyline', $value);
    }

    public function director($value): Builder {
        return $this->whereInArray('director', $value);
    }

    public function writer($value): Builder {
        return $this->whereInArray('writer', $value);
    }

    public function cast($value): Builder {
        return $this->whereInArray('cast', $value);
    }

    public function createdAt($value): Builder {
        return $this->whereDateOrBetweenDates('created_at', $value);
    }

    public function updatedAt($value): Builder {
        return $this->whereDateOrBetweenDates('updated_at', $value);
    }

}