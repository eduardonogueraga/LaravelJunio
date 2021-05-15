<?php

namespace App\Filters;
use App\Rules\SortableColumn;
use App\Sortable;

class ProfessionFilter extends QueryFilter
{
    protected $aliasses = [
        'jornada' => 'workday',
        'salario' => 'salary',
        'titulo' => 'title',
        'nivel' => 'academic_level',
        'perfiles' => 'profiles_count',
    ];

    public function getColumnName($alias)
    {
        return $this->aliasses[$alias] ?? $alias;
    }


    public function rules(): array
    {
        return [
            'search' => 'filled',
            'workday' => 'in:Jornada completa,Media jornada,Temporal,Indefinido,Beca',
            'academic_level' => 'in:Estudios universitarios,Educación secundaria,Estudios de postgrado,Enseñanza básica',
            'language' => 'in:with,without',
            'transport' => 'in:with,without',
            'experience' => 'in:with,without',
            'order' => [new SortableColumn(['titulo', 'jornada','nivel', 'salario', 'perfiles'])], //Profile count es el alias el count (usa alias para lo que viene de la vista)
        ];
    }


    public function experience($query, $experience)
    {
        if($experience == 'with'){return $query->where('experience', '!=', null);}
        return $query->where('experience', null);
    }

    public function transport($query, $transport, $value = 0)
    {
        if($transport=='with'){$value=1;}
        return $query->where('vehicle', $value);
    }

    public function language($query, $language, $value = 0)
    {
        if($language=='with'){$value=1;}
        return $query->where('language', $value);
    }


    public function search($query, $search)
    {
        return $query->where(function ($query) use ($search){
            return $query->where('title', 'like', "%$search%")
                ->orWhere('workday', 'like', "%$search%");
        });
    }

    public function workday($query, $workday)
    {
        return $query->where('workday', $workday);
    }

    public function academic_level($query, $academic_level)
    {
        return $query->where('academic_level', $academic_level);
    }

    public function order($query, $value)
    {
        [$column, $direction] = Sortable::info($value);
        $query->orderBy($this->getColumnName($column), $direction);
    }

}