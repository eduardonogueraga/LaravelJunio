<?php

namespace App\Http\Controllers;

use App\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function index()
    {
        return view('professions.index', [
            'professions' => Profession::withCount('profiles')->orderBy('title')->get(),
        ]);
    }

    public function create(Profession $profession)
    {
        return view('professions.create',['profession' => $profession]);
    }

    public function store()
    {
        $post = request()->validate($this->getRules(), $this->getMessage());

        Profession::create(['title' => $post['title'],]);

        return redirect()->route('professions.index');
    }

    public function edit(Profession $profession)
    {
        return view('professions.edit', ['profession' => $profession]);
    }

    public function update(Profession $profession)
    {
        $post = request()->validate($this->getRules(), $this->getMessage());

        $profession->update(['title' => $post['title'],]);

        return redirect()->route('professions.index');
    }


    public function destroy(Profession $profession)
    {
        abort_if($profession->profiles()->exists(), 400, 'Cannot delete a profession linked to a profile');

        $profession->delete();

        return redirect()->route('professions.index');
    }

    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'title' => ['required', 'regex:/^[a-zA-ZáéíóúñÑ\s]+$/']
        ];
    }

    /**
     * @return string[]
     */
    public function getMessage(): array
    {
        return [
            'title.required' => 'El campo titulo es obligatorio'
        ];
    }
}
