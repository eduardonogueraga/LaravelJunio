<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'active' => 'bool',
        'last_login_at' => 'datetime', //Casteos
    ];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new UserQuery($query); //Enlaza el modelo y las querys con builder
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class)->withDefault();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class)->withDefault();
    }

    public function setStateAttribute($value)
    {
        $this->attributes['active'] = ($value == 'active'); //Si el valor es active devuelve true
    }

    public function getStateAttribute()
    {
        if ($this->active !== null) { //si no es nulo
            return $this->active ? 'active' : 'inactive'; //Si es true dame un active
        }
    }

    public function getNameAttribute()
    {
        return $this->first_name;
    }

    public function getLastAttribute()
    {
        return $this->last_name;
    }

    public function delete() //Redefine el metodo delete
    {
        DB::transaction(function () {
            if (parent::delete()) { //SI  se hace un borrado padre
                $this->profile()->delete();

                DB::table('skill_user')
                    ->where('user_id', $this->id)
                    ->update(['deleted_at' => now()]);
            }
        });
    }
}
