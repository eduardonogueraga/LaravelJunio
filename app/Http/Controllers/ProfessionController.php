<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProfessionRequest;
use App\Http\Requests\UpdateProfessionRequest;
use App\Profession;
use App\Sortable;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function index(Sortable $sortable)
    {
        $profession = Profession::query()
            ->with('teams') //Para la relacion con teams N:M
            ->withCount('profiles')
            ->applyFilters()
            ->orderBy('title')
            ->paginate();

        $sortable->appends($profession->parameters());

        return view('professions.index', [
            'professions' => $profession,
            'sortable' => $sortable,
        ]);
    }

    public function show(Profession $profession)
    {
        return view('professions.show', ['profession' => $profession]);
    }

    public function create(Profession $profession)
    {
        return view('professions.create',['profession' => $profession]);
    }

    public function store(CreateProfessionRequest $request)
    {
        $request->createProfession();
        return redirect()->route('professions.index');
    }

    public function edit(Profession $profession)
    {
        return view('professions.edit', ['profession' => $profession]);
    }

    public function update(UpdateProfessionRequest $request, Profession $profession)
    {
        $request->updateProfession($profession);
        return redirect()->route('profession.show', ['profession' => $profession]);
    }


    public function destroy(Profession $profession)
    {
        abort_if($profession->profiles()->exists(), 400, 'Cannot delete a profession linked to a profile');

        $profession->delete();

        return redirect()->route('professions.index');
    }

}
