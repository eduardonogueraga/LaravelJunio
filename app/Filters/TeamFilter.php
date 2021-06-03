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
        'proyectos' => 'active_projects_count'
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
            'projects' => 'in:with,without',
            'actives' => 'array|exists:projects,id',
            'headquarter' => 'exists:headquarters,name',
            'professions' => 'array|exists:professions,id',
            'order' => [new SortableColumn(['nombre_empresa','trabajadores','numero_profesiones', 'proyectos'])],
        ];
    }

    public function actives($query, $actives)
    {
        //Numero de entradas del pivote que tengan los id de proyecto esperados
        $subquery = DB::table('project_team AS pte')
            ->selectRaw('COUNT(pte.id)')
            ->whereColumn('pte.team_id', 'teams.id')
            ->whereIn('pte.project_id', $actives);

           $query->whereQuery($subquery, count($actives));
    }

    public function projects($query, $project, $method = 'has')
    {
        $project == 'without'? $method='doesntHave' : '';
        return $query->$method('activeProjects');
    }

    public function professions($query, $professions)
    {
        $subquery = DB::table('profession_team AS pt')
            ->selectRaw('COUNT(pt.id)')
            ->whereColumn('pt.team_id', 'teams.id') //enlaza con el modelo team.id
            ->whereIn('pt.profession_id', $professions);

        $query->whereQuery($subquery, count($professions));

    }

    public function headquarter($query, $headquarter)
    {
        return $query->whereHas('headquarters', function ($query) use ($headquarter){
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
                ->orWhereHas('headquarters', function ($query) use ($search){
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