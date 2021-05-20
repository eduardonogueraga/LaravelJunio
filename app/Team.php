<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    function professions()
    {
        return $this->belongsToMany(Profession::class);
    }

    function users()
    {
        return $this->hasMany(User::class);
    }

    public function newEloquentBuilder($query)
    {
        return new TeamQuery($query);
    }
}
