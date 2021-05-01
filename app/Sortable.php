<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Sortable
{
    protected $currentUrl; //Se carga en el appservice provider
    protected $query = []; //desde parameters en controlador y lengaware (url param)

    public function __construct($currentUrl)
    {
        $this->currentUrl = $currentUrl;
    }

    public function url($column) //Recibe el nombre del campo y construye la url
    {
        if ($this->isSortingBy($column)) { //Se estaba ordenando por asc?
            return $this->buildSortableUrl($column . '-desc');
        }
        return $this->buildSortableUrl($column);
    }

    protected function buildSortableUrl($order)
    {
        //Arr::query devuelve en formato de GET para url (skill=1&skill=2 etc)
        return $this->currentUrl . '?' . Arr::query(array_merge($this->query, ['order' => $order]));
        //Manda la url modificada con los datos previamente setados
    }

    protected function isSortingBy($column)
    {
        return Arr::get($this->query, 'order') == $column;
        //Si el campo es igual es decir no lleva -desc devuelve true
    }

    public function classes($column)
    {
        if ($this->isSortingBy($column)) { //Si es ascendente
            return 'link-sortable link-sorted-up';
        }

        if ($this->isSortingBy($column . '-desc')) { //Si es descendente
            return 'link-sortable link-sorted-down';
        }

        return 'link-sortable'; //Si no esta en el campo el foco ambos
    }

    public function appends(array $query) //Appeend del controller
    {
        $this->query = $query;  //Setea la query
    }

    public static function info($order)
    {
        if (Str::endsWith($order, '-desc')) {
            return [Str::substr($order, 0, -5), 'desc']; //Le quita el -desc
        } else {
            return [$order, 'asc'];
        }
    }
}
