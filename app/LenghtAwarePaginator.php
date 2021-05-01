<?php

namespace App;

use Illuminate\Pagination\LengthAwarePaginator;

class LenghtAwarePaginator extends LengthAwarePaginator
{
    public function parameters()
    {
        return $this->query; //devuelve la query pero de la url ojo
    }
}
