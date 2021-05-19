<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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

    public function updateTeam($team)
    {
        $team->update([
            'name' => $this->name,
        ]);

        $team->professions()->sync($this->professions ?: []);
    }
}