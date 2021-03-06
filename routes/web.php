<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/usuarios', 'UserController@index')->name('users.index');

Route::get('usuarios/nuevo', 'UserController@create')->name('users.create');
Route::post('/usuarios', 'UserController@store')->name('users.store');
Route::get('/usuarios/{user}/editar', 'UserController@edit')->name('users.edit');
Route::put('/usuarios/{user}', 'UserController@update')->name('users.update');
Route::delete('/usuarios/{id}', 'UserController@destroy')->name('users.destroy');
Route::get('/usuarios/papelera', 'UserController@index')->name('users.trashed');
Route::get('/usuarios/{id}/restore', 'UserController@restore')->name('users.restore');
Route::get('usuarios/{user}', 'UserController@show')->where('user', '[0-9]+')->name('users.show');
Route::patch('/usuarios/{user}/papelera', 'UserController@trash')->name('users.trash');


Route::get('/editar-perfil/', 'ProfileController@edit')->name('profile.edit');
Route::put('/editar-perfil/{user}', 'ProfileController@update')->name('profile.update');

Route::get('/profesiones/', 'ProfessionController@index')->name('professions.index');

Route::get('/profesiones/create', 'ProfessionController@create')->name('profession.create');
Route::post('/profesiones/', 'ProfessionController@store')->name('profession.store');
Route::get('/profesiones/{profession}/editar', 'ProfessionController@edit')->name('profession.edit');
Route::put('/profesiones/{profession}', 'ProfessionController@update')->name('profession.update');
Route::get('/profesiones/{profession}/show', 'ProfessionController@show')->name('profession.show');
Route::delete('/profesiones/{profession}', 'ProfessionController@destroy')->name('professions.destroy');

Route::get('/equipos/', 'TeamController@index')->name('teams.index');
Route::get('/equipos/{team}/show', 'TeamController@show')->name('teams.show');

Route::get('/equipos/crear', 'TeamController@create')->name('teams.create');
Route::post('/equipos/', 'TeamController@store')->name('teams.store');
Route::get('/equipos/{team}/editar', 'TeamController@edit')->name('teams.edit');
Route::put('/equipos/{team}', 'TeamController@update')->name('teams.update');
Route::get('/equipos/papelera', 'TeamController@index')->name('teams.trashed'); //Para listar

Route::get('/equipos/{team}/restore', 'TeamController@restore')->name('teams.restore');
Route::patch('/equipos/{team}/papelera', 'TeamController@trash')->name('teams.trash'); //Para borrar
Route::delete('/equipos/{team}', 'TeamController@destroy')->name('teams.destroy');


Route::get('/proyectos/', 'ProjectController@index')->name('projects.index');
Route::get('/proyectos/{project}/show', 'ProjectController@show')->name('projects.show');
Route::get('/proyectos/crear', 'ProjectController@create')->name('projects.create');
Route::post('/proyectos/' , 'ProjectController@store')->name('projects.store');
Route::get('proyectos/{project}/editar', 'ProjectController@edit')->name('projects.edit');
Route::put('proyectos/{project}', 'ProjectController@update')->name('projects.update');
Route::delete('/proyectos/{project}', 'ProjectController@destroy')->name('projects.destroy');


Route::get('/habilidades/', 'SkillController@index')->name('skills.index');

Route::get('/saludo/{name}/{nickname?}', 'WelcomeUserController');
