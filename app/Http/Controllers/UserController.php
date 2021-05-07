<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Profession;
use App\Skill;
use App\Sortable;
use App\Team;
use App\User;
use App\UserFilter;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Sortable $sortable)
    {
        $users = User::query()
            ->with('team', 'skills', 'profile.profession') //relaciones
            ->withLastLogin() //Pilla subconsula del user query
            ->withTwitter()
            ->onlyTrashedIf(request()->routeIs('users.trashed'))  //Pilla solo los borrados si se cumple esa ruta
            ->applyFilters() //Aplica los filtros
            ->orderByDesc('created_at') //Ordenacion por defecto
            ->paginate(); //Al usar paginate el obj se convierte en awarepaginator y tiene mas propiedades

        $sortable->appends($users->parameters()); //Setea la query en sortable y pasa los paramentros URL

        return view('users.index', [
            'users' => $users,
            'view' => request()->routeIs('users.trashed') ? 'trash' : 'index', //Pasa una vista u otra
            'skills' => Skill::orderBy('name')->get(),
            'teams' => Team::orderBy('name')->get(),
            'checkedSkills' => collect(request('skills')), //Pasa la coleccion de valores validos
            'sortable' => $sortable, //Pasa el obj sortable
        ]);
    }

    public function create()
    {
        return $this->form('users.create', new User); //Pasa la instancia para inyectar
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return $this->form('users.edit', $user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $request->updateUser($user);

        return redirect()->route('users.show', $user);
    }

    public function destroy($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();

        $user->forceDelete();

        return redirect()->route('users.trashed');
    }

    public function trash(User $user)
    {
        $user->profile()->delete();
        $user->delete();

        return redirect()->route('users.index');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();
        $user->profile()->restore();
        $user->restore();

        return redirect()->route('users.index');
    }

    public function form($view, User $user)
    {
        return view($view, [
            'professions' => Profession::orderBy('title', 'ASC')->get(),
            'skills' => Skill::orderBy('name', 'ASC')->get(),
            'user' => $user,
        ]);
    }
}
