<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function newEloquentBuilder($query)
    {
        return new ProfessionQuery($query);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
