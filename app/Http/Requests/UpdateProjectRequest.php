<?php

namespace App\Http\Requests;

use App\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class UpdateProjectRequest extends FormRequest
{

    public function rules()
    {
        $oldFinishDate = Carbon::parse($this->project->finish_date)->startOfDay(); //Pone los stamps a 0
        $newFinishDate = Carbon::createFromFormat('d/m/Y', $this->finish_date)->startOfDay();

        return [
            'title' => 'required|regex:/^[a-zA-ZáéíóúñÑ\s\.\,]+$/|min:10',
            'about' => 'required|string|min:10',
            'budget' => 'required|numeric|min:1000|max:10000',
            'status' => 'required|boolean',
            'finish_date' => ['required',
                'date_format:d/m/Y',
                ($oldFinishDate != $newFinishDate)?'after:start_day' : ''
            ],
            'teams' => 'array|required|exists:teams,id'
        ];
    }

    public function authorize()
    {
        return true;
    }


    public function updateProject(Project $project)
    {

        $project->update([
            'title' => $this->title,
            'about' => $this->about,
            'budget' => $this->budget,
            'finish_date' => Carbon::createFromFormat('d/m/Y', $this->finish_date),
            'status' => $this->status,
        ]);

        $project->teams()->sync($this->teams);

    }
}