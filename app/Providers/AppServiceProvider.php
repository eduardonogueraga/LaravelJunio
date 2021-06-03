<?php

namespace App\Providers;

use App\Http\ViewComposers\UserFieldComposer;
use App\Profession;
use App\Skill;
use App\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::aliasComponent('shared._card', 'card');

        Paginator::useBootstrap();

        $this->app->bind(LengthAwarePaginator::class, \App\LenghtAwarePaginator::class); //re mezcla la clase origen con la nuestra
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Sortable::class, function ($app) { //Bindea la url
            return new Sortable(request()->url()); //Prepara la / de nuestro dominio y crea la instancua sortable
        });
    }
}
