<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    protected $dates = ['finish_date']; //Parsear a carbon

    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->with('users')
            ->withCount('users') //Lo añado desde la relacion
            ->withPivot(['is_head_team']);
    }

    public function newEloquentBuilder($query)
    {
        return new ProjectQuery($query);
    }



}