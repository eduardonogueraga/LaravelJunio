<?php

namespace App\Rules;

use App\Sortable;
use Illuminate\Contracts\Validation\Rule;

class SortableColumn implements Rule
{
    private $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns; //Le llega un array con las colunas de rules en UserFilter
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //Esta clase sirve de filtro para el user controler en el tema de sortables para que lo valores sean validos
        //$attribute en dd saca order

        if (! is_string($value)) { //Tiene que ser cadena
            return false;
        }

        [$column] = Sortable::info($value);  //Saca si el campo en array con el valor y su orden (ej. email, asc)

        return in_array($column, $this->columns); //Saca si el campo esta o no esta
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
