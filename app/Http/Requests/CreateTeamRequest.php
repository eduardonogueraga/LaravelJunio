<?php

namespace App\Http\Requests;

use App\Team;
use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
{
    public function rules()
    {
        return [
          'name' => 'required',
            'professions' => [
                'nullable',
                'array'
            ]

        ];
    }

    public function authorize()
    {
        return true;
    }

    public function createTeam()
    {
       $team = Team::create([
            'name' => $this->name,
        ]);

        $team->professions()->attach($this->professions ?: []);
    }
}