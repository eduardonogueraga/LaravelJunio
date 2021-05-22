<?php

namespace App\Filters;

use App\Rules\SortableColumn;
use App\Sortable;
use Illuminate\Support\Facades\DB;

class TeamFilter extends QueryFilter
{
    protected $aliasses = [
        'nombre_empresa' => 'name',
        'trabajadores' => 'users_count',
        'numero_profesiones' => 'professions_count',
    ];

    public function getColumnName($alias)
    {
        return $this->aliasses[$alias] ?? $alias;
    }

    public function rules(): array
    {
        return [
            'search' => 'filled',
            'worker' => 'in:with,without',
            'profession' => 'in:with,without',
            'headquarter' => 'exists:headquarters,name',
            'professions' => 'array|exists:professions,id',
            'order' => [new SortableColumn(['nombre_empresa','trabajadores','numero_profesiones'])],
        ];
    }

    public function professions($query, $professions)
    {
        $subquery = DB::table('profession_team AS pt')
            ->selectRaw('COUNT(pt.id)')
            ->whereColumn('pt.team_id', 'teams.id')
            ->whereIn('pt.profession_id', $professions);

        $query->whereQuery($subquery, count($professions));

    }

    public function headquarter($query, $headquarter)
    {
        return $query->whereHas('headquarter', function ($query) use ($headquarter){
            return $query->whereName($headquarter);
        });
    }

    public function worker($query, $worker)
    {
        if($worker=='with')
        {
            return $query->whereHas('users');
        }
        return $query->whereDoesnthave('users');
    }

    public function profession($query, $worker)
    {
        if($worker=='with')
        {
            return $query->whereHas('professions');
        }
        return $query->whereDoesnthave('professions');
    }

    public function search($query, $search)
    {
        return $query->where(function ($query) use($search){
            return $query->where('name', 'like', "%$search%")
                ->orWhereHas('headquarter', function ($query) use ($search){
                    return $query->where('name', 'like',"%$search%" );
                });
        });
    }

    public function order($query, $value)
    {
        [$column, $direction] = Sortable::info($value);
        $query->orderBy($this->getColumnName($column), $direction);
    }
}