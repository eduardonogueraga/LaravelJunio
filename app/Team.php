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
        return $this->hasMany(User::class)->with('team'); //Para la relacion inversa ($users as user->team)
    }

    public function headquarters()
    {
        return $this->hasMany(Headquarter::class);
    }

    public function mainHeadquarter()
    {
        return $this->hasOne(Headquarter::class)->where('is_central', 1);
    }

    public function leader()
    {
        return$this->hasOne(User::class)->where('is_leader', 1)->withDefault([
            'first_name' => 'Sin lider'
        ]);
    }

    public function projects()
    {
        return  $this->belongsToMany(Project::class);
    }

    public function activeProjects()
    {
        return  $this->belongsToMany(Project::class)->where('status', 1);
    }


    public function newEloquentBuilder($query)
    {
        return new TeamQuery($query);
    }
}
