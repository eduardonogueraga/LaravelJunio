<?php

namespace App\Filters;
use App\Rules\SortableColumn;

class ProfessionFilter extends QueryFilter
{
    public function rules(): array
    {
        return [
            'search' => 'filled',
            'workday' => 'in:Jornada completa,Media jornada,Temporal,Indefinido,Beca',
            'academic_level' => 'in:Estudios universitarios,Educación secundaria,Estudios de postgrado,Enseñanza básica',
            'language' => 'in:with,without',
            'transport' => 'in:with,without',
            'experience' => 'in:with,without',
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

}