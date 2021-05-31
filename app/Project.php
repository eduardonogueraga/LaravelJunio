<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->with('users')
            ->withCount('users') //Lo añado desde la relacion
            ->withPivot(['is_head_team']);
    }



}