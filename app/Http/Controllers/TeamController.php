<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Profession;
use App\Team;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;

class TeamController extends Controller
{

    public function index()
    {
        $teams = Team::query()
        ->with('users','professions')
        ->withCount('users')
        ->withCount('professions')
        ->orderBy('name')
        ->paginate();

        return view('teams.index', [
            'teams' => $teams,
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

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}