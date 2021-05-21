<?php

namespace App\Filters;

use App\Login;
use App\Sortable;
use App\Rules\SortableColumn;
use App\Team;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserFilter extends QueryFilter
{
    protected $aliasses = [
        'date' => 'created_at',
        'login' => 'last_login_at',
    ];

    public function getColumnName($alias) //getter de alias para el order
    {
        return $this->aliasses[$alias] ?? $alias;  //Si existe el alis bien sino se devuelve el paramtro
    }

    public function rules(): array
    {
        return [
            'occupation' => 'in:employed,unemployed',
            'profession' => 'exists:professions,title',
            'country' => 'exists:countries,name',
            'twitter' => 'in:with,without',
            'teamName' => 'exists:teams,name',
            'team' => 'in:with_team,without_team',
            'search' => 'filled',
            'state' => 'in:active,inactive',
            'role' => 'in:user,admin',
            'skills' => 'array|exists:skills,id',
            'from' => 'date_format:d/m/Y',
            'to' => 'date_format:d/m/Y',
            'order' => [new SortableColumn(['first_name', 'last_name','email', 'date', 'login', 'twitter'])],  //Crea una instancia de sortcolum y le pasa los campos validos
        ];
    }

    public function occupation($query, $occupation, $method = 'has')
    {
        $occupation == 'employed'?:$method = 'doesntHave';  //has y doesntHave parametrizado ni te rayes
        return $query->$method('profile.profession');
    }

    public function profession($query, $profession)
    {
        return $query->whereHas('profile.profession', function ($query) use ($profession){
            return $query->where('title', $profession);
        });
    }

    public function country($query, $country)
    {
        return $query->whereHas('address.country', function ($query) use ($country){
            $query->where('name', $country);
        });
    }

    public function twitter($query, $twitter, $operator = '=')
    {
        if($twitter == 'with'){$operator ='!=';}

        return $query->whereHas('profile', function ($query) use ($operator){
            $query->where('twitter',$operator, null);
        });
    }

    public function teamName($query, $teamName)
    {
        return $query->whereHas('team', function ($query) use ($teamName){
            $query->where('name', $teamName);
        });
    }

    public function team($query, $team)
    {
        if ($team == 'with_team') {
            return  $query->has('team');
        } elseif ($team == 'without_team') {
            return  $query->doesntHave('team');
        }
    }

    public function search($query, $search)
    {
        return $query->where(function ($query) use ($search){ //Para que el where ponga el parentesis
            return $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhereHas('team', $this->subQuery($search, 'name'))
                ->orWhereHas('profile', $this->subQuery($search, 'twitter'))
                ->orWhereHas('profile.profession', $this->subQuery($search, 'title'));
        });
    }

    public function state($query, $state)
    {
        return $query->where('active', $state == 'active');  //la segunda condicion seria un true OJO
    }

    public function skills($query, $skills)
    {
        //Basicamente busca la persona que tenga todas las que salen por eso compara con el count del arr

        $subquery = DB::table('skill_user AS s')
            ->selectRaw('COUNT(s.id)')
            ->whereColumn('s.user_id', 'users.id')
            ->whereIn('skill_id', $skills); //Todos los users q tengan habilidad x en el arr de skikls

        $query->whereQuery($subquery, count($skills)); //Es la funcion del querybuilder para hacer subconsultas y bindear parms
    }

    public function from($query, $date)
    {
        $date = Carbon::createFromFormat('d/m/Y', $date);  //Parsea a carbon

        $query->whereDate('created_at', '>=', $date);
    }

    public function to($query, $date)
    {
        $date = Carbon::createFromFormat('d/m/Y', $date);

        $query->whereDate('created_at', '<=', $date);
    }

    public function order($query, $value)
    {
        //Lo que hace esto es dar valor a ambos con lo que se recibe del array en orden
        //colunm == first_name direccion == asc

        [$column, $direction] = Sortable::info($value); //Se le envia el nombre del campo a sortear (first_name)

        $query->orderBy($this->getColumnName($column), $direction); //Si la columna tiene alias lo cambia sino nada
    }


    public function subQuery($search, $column) //Refactorizacion de mis orWhereHas
    {
        return function ($query) use ($search, $column) {
            $query->where($column, 'like', "%{$search}%");
        };
    }
}
