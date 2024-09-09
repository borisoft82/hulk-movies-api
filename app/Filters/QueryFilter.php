<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter {

    protected $builder;
    protected $request;
    protected $sortable = [];

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function apply(Builder $builder) {
        $this->builder = $builder;

        foreach($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }

    public function filter(array $arr) {
        foreach($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    protected function sort($value) {
        $sortAttributes = explode(',', $value);

        foreach($sortAttributes as $sortAttribute) {
            $direction = 'asc';

            if (strpos($sortAttribute, '-') === 0) {
                $direction = 'desc';
                $sortAttribute = substr($sortAttribute, 1);
            }

            if (!in_array($sortAttribute, $this->sortable) && !array_key_exists($sortAttribute, $this->sortable)) {
                continue;
            }

            $columnName = $this->sortable[$sortAttribute] ?? null;

            if ($columnName === null) {
                $columnName = $sortAttribute;
            }

            $this->builder->orderBy($columnName, $direction);
        }
    }

    protected function whereLikeString(string $column, $value): Builder {
        $likeStr = str_replace('*', '%', $value);
        return $this->builder->where($column, 'LIKE', $likeStr);
    }

    protected function whereInArray(string $column, $value): Builder {
        return $this->builder->whereIn($column, explode(',', $value));
    }

    protected function whereDateOrBetweenDates($timestamp, $value): Builder {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween($timestamp, $dates);
        }

        return $this->builder->whereDate($timestamp, $value);
    }

}