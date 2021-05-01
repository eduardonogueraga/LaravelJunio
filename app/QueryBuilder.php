<?php

namespace App;

use App\Filters\QueryFilter;
use http\Exception\BadMethodCallException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class QueryBuilder extends Builder
{
    private $filters;

    public function whereQuery($subquery, $operator, $value = null) //Prepara subconsultas bindeadas para userfilter
    {
        $this->addBinding($subquery->getBindings());
        $this->where(DB::raw("({$subquery->toSql()})"), $operator, $value);  //Si no hay value operador hace de value :/

        return $this;
    }

    public function onlyTrashedIf($value) //Pilla la ruta del isRoute
    {
        if ($value) { //Si es true devuelve only trashed
            $this->onlyTrashed();
        }

        return $this;
    }

    public function filterBy(QueryFilter $filters, array $data)
    {
        // 3º
        $this->filters = $filters; //Filters es la instancia de \App\Filters\UserFilter (guarda la instancia de filters del momento)

        return $filters->applyTo($this, $data);  //This es la query
    }

    public function applyFilters(array $data = null)
    {
        // 1º
        //Llama a filtro con instancias concretas de cada clase y los datos del form de filtro
        // dd(request()->all());
        //las requests son los param q van por get en url

        return $this->filterBy($this->newQueryFilter(), $data ?: request()->all());
    }

    public function newQueryFilter()
    {
        // 2º
        if (method_exists($this->model, 'newQueryFilter')) {
            return $this->model->newQueryFilter(); //si existe un metodo en  el modelo lo devuelve
        }

        if (class_exists($filterClass = '\App\Filters\\'.class_basename($this->model).'Filter')) {
            return new $filterClass;  //Devuelve en nuestro caso una instancia
        }

        throw new BadMethodCallException(
            sprintf('No query filter was found for the model [%s]', get_class($this->model))
        );
    }

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        //Redefinicion del paginator (para que solo los url param validados se guarden)
        $paginator = parent::paginate($perPage, $columns, $pageName, $page);

        if ($this->filters) { //Si encuentra filtros  validos se los aplica
            $paginator->appends($this->filters->valid());
        }

        return $paginator;
    }
}
