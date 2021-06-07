<?php

namespace App\Http\Requests;

use App\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class UpdateProjectRequest extends FormRequest
{

    public function rules()
    {

        return [
            'title' => 'required|regex:/^[a-zA-ZáéíóúñÑ\s\.\,]+$/|min:10',
            'about' => 'required|string|max:1000',
            'budget' => 'required|numeric|min:1000|max:10000',
            'status' => 'required|boolean',
            'finish_date' => ['required','present',
                'date_format:d/m/Y',
                ($this->isDateChanged())?'after:start_day' : ''
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
            'finish_date' => Carbon::createFromFormat('d/m/Y', $this->finish_date)->startOfDay(),
            'status' => $this->status,
        ]);

        $project->teams()->sync($this->teams);

    }


    public function isDateChanged()
    {
        if (!is_null($this->finish_date) && preg_match("/^\d{2}\/\d{2}\/\d{4}$/i", $this->finish_date)) {

        $oldFinishDate = Carbon::parse($this->project->finish_date)->startOfDay(); //Pone los stamps a 0
        $newFinishDate = Carbon::createFromFormat('d/m/Y', $this->finish_date)->startOfDay();

            if($oldFinishDate != $newFinishDate){
                return true;
            }
        }
        return false;
    }
}