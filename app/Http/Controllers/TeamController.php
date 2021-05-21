<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Profession;
use App\Team;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;
use SebastianBergmann\Template\Template;

class TeamController extends Controller
{

    public function index()
    {
        $teams = Team::query()
        ->with('users','professions')
        ->withCount('users')
        ->withCount('professions')
        ->onlyTrashedIf(request()->routeIs('teams.trashed')) //Controla en funcion de la ruta que lo llama
        ->applyFilters()
        ->orderBy('name')
        ->paginate();

        return view('teams.index', [
            'teams' => $teams,
            'view' => request()->routeIs('teams.trashed') ? 'trash' : 'index',
        ]);
    }

    public function create(Team $team)
    {
        $professions = Profession::orderBy('title')->get();
        return view('teams.create', ['team' => $team, 'professions' => $professions ]);
    }

    public function store(CreateTeamRequest $request)
    {
        $request->createTeam();
        return redirect()->route('teams.index');
    }

    public function show(Team $team)
    {
        return view('teams.show', ['team' => $team]);
    }

    public function edit(Team $team)
    {
        $professions = Profession::orderBy('title')->get();
        return view('teams.edit', compact('team', 'professions'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $request->updateTeam($team);
        return redirect()->route('teams.show', compact('team'));
    }

    public function trash(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.trashed');
    }

    public function restore($id)
    {
        $team = Team::onlyTrashed()->where('id', $id)->firstOrFail();
        $team->restore();
        return redirect()->route('teams.index');
    }

    public function destroy($id)
    {
        $team = Team::onlyTrashed()->where('id', $id)->firstOrFail();
        abort_if($team->users()->exists(), 400, 'Cannot delete a team linked to a user');
        $team->forceDelete();
        return redirect()->route('teams.trashed');
    }
}