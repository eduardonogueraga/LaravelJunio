<?php

namespace App\Filters;

class TeamFilter extends QueryFilter
{
    public function rules(): array
    {
        return [
            'search' => 'filled',
        ];
    }

    public function search($query, $search)
    {
        return $query->where('name', 'like', "%$search%");
    }
}