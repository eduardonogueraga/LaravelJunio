<?php

namespace App\Http\Controllers;

use App\Headquarter;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Profession;
use App\Project;
use App\Sortable;
use App\Team;
use App\User;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;
use SebastianBergmann\Template\Template;

class TeamController extends Controller
{

    public function index(Sortable $sortable)
    {
        $teams = Team::query()
        ->with('users','professions', 'headquarters', 'mainHeadquarter', 'leader', 'activeProjects', 'projects')
        ->withCount('activeProjects')
        ->withCount('users')
        ->withCount('professions')
        ->onlyTrashedIf(request()->routeIs('teams.trashed')) //Controla en funcion de la ruta que lo llama
        ->applyFilters()
        ->orderBy('name')
        ->paginate();

        $sortable->appends($teams->parameters());

        return view('teams.index', [
            'teams' => $teams,
            'view' => request()->routeIs('teams.trashed') ? 'trash' : 'index',
            'headquarters' => Headquarter::query() //Solo equipos com team
                            ->with('team')
                            ->whereHas('team')
                            ->orderBy('name', 'ASC')->get(),
            'professions' => Profession::query() //Solo quiero professiones que esten en algun equipo
                            ->with('teams')
                            ->whereHas('teams')
                            ->orderBy('title', 'ASC')->get(),
            'projects' => Project::query()
                            ->with('teams')
                            ->whereHas('teams')
                            ->where('status', false)
                            ->orderBy('title', 'ASC')->get(),
            'checkedProfessions' => collect(request('professions')), //La memoria que le viene del request
            'checkedProjects' => collect(request('actives')),
            'sortable' => $sortable,
        ]);
    }

    public function create(Team $team)
    {
        $professions = Profession::orderBy('title')->get();
        $users = User::query() //Candidatos a leader
            ->with('team')
            ->doesntHave('team')
            ->orderBy('created_at', 'ASC')
            ->get();

        return view('teams.create', ['team' => $team, 'professions' => $professions, 'leaders' => $users]);
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
        $leaders = User::query() //Candidatos a leader
        ->with('team')
            ->doesntHave('team')
            ->orWhere('id', $team->leader->id) //Para incluirlo
            ->orderBy('created_at', 'ASC')
            ->get();
        return view('teams.edit', compact('team', 'professions', 'leaders'));
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
        abort_if($team->users()->count() > 1, 400, 'Cannot delete a team linked to a user');
        $team->forceDelete();
        return redirect()->route('teams.trashed');
    }
}