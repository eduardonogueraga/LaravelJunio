<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use App\Sortable;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProjectController extends Controller
{

    public function index(Sortable $sortable)
    {
        $projects = Project::query()
                            ->with('teams')
                            ->applyFilters()
                            ->orderBy('title', 'ASC')
                            ->paginate();

        $sortable->appends($projects->parameters());

        return view('projects.index', compact('projects', 'sortable'));
    }


    public function create(Project $project)
    {

        $teams = Team::query()
            ->with('users')
            ->with('projects')
            ->has('users')
            ->withCount('users')
            ->withCount('projects')
//            ->whereHas('projects', function ($query){
//                return $query->where('status', 0);
//            })
            ->orderBy('name')
            ->get();


        return  view('projects.create', [
            'project' => $project,
            'teams' => $teams,
        ]);
    }


    public function store(CreateProjectRequest $request)
    {
        $request->createProject();
        return redirect(route('projects.index'));
    }


    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }


    public function edit(Project $project)
    {

        $teams = Team::query()
            ->with('users')
            ->with('projects')
            ->has('users')
            ->withCount('users')
            ->withCount('projects')
//            ->whereHas('projects', function ($query){
//                return $query->where('status', 0);
//            })
            ->orderBy('name')
            ->get();

        return view('projects.edit', [
            'project' => $project,
             'teams' => $teams,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $oldFinishDate = Carbon::parse($project->finish_date)->startOfDay(); //Pone los stamps a 0
        $newFinishDate = Carbon::createFromFormat('d/m/Y', $request->finish_date)->startOfDay();

        $request->validate([
            'finish_date' => ['required',
                            'date_format:d/m/Y',
                            ($oldFinishDate != $newFinishDate)?'after:start_day' : ''
            ],
        ]);


        $request->updateProject($project);
        return redirect(route('projects.show', ['project' => $project]));
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