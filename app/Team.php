<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function professions()
    {
        return $this->belongsToMany(Profession::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function headquarters()
    {
        return $this->hasMany(Headquarter::class);
    }

    public function mainHeadquarter()
    {
        return $this->hasOne(Headquarter::class)->where('is_central', 1);
    }

    public function newEloquentBuilder($query)
    {
        return new TeamQuery($query);
    }
}
