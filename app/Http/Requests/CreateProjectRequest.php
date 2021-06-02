<?php

namespace App\Http\Requests;

use App\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class CreateProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|regex:/^[a-zA-ZáéíóúñÑ\s]+$/|min:10',
            'about' => 'required|string|min:10',
            'budget' => 'required|numeric|min:1000|max:10000',
            'finish_date' => 'required|date_format:d/m/Y|after:start_date',
            'teams' => 'array|required|exists:teams,id'
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function createProject()
    {
        $project = Project::create([
            'title' => $this->title,
            'about' => $this->about,
            'budget' => $this->budget,
            'finish_date' => Carbon::createFromFormat('d/m/Y', $this->finish_date), //Formato de fecha que pasamos
            'status' => false,
        ]);

        foreach ($this->teams as $key => $team){
           $project->teams()->attach($team, ['is_head_team' => $key === array_key_first($this->teams)]);
        }

    }
}