<?php

namespace App\Filters;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected $valid = [];  //Array abs

    abstract public function rules(): array; //Funcion abs

    public function applyTo($query, array $filters) //Prepara los datos para validacion
    {
        $rules = $this->rules(); //Se actualiza lo que tenga en rules
        //validator pilla las validaciones del rules hijo de user filter (interseccion para pillar solo las que se demandan)
        $validator = Validator::make(array_intersect_key($filters, $rules), $rules); //Solo campos que tengan una validcion correspondiente

        $this->valid = $validator->valid(); //Los datos del validador pasan al metodo valid
        //Carga los validos en el array privado de valid

        //Ejmeplo  "state" => "active"
        foreach ($this->valid as $name => $value) {
            $this->applyFliters($query, $name, $value); //Manda indice y valor junto con query a apply filters
        }

        return $query;
    }

    public function applyFliters($query, $name, $value) //Lanza los scopes
    {
        $method = Str::studly($name); //Pone el indice en mayus Ejempli State

        if (method_exists($this, $method)) { //Busca el metodo state y le pasa la query y el valor
            $this->$method($query, $value);
        } else {
            $query->where($name, $value);  //Si no hay scope busca una columna de la tabla
        }
    }

    public function valid()
    {
        return $this->valid;
    }
}
