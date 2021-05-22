<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateTeamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/'],
            'headquarter' => ['required',
                              'regex:/^[a-zA-Z0-9áéíóúñÑ\s]+$/',
                              'unique:headquarters,name,' .$this->team->headquarter->id], //Fitaje que busca id siempre y las comas del unique
            'professions' => [
                'nullable',
                'array',
                'exists:professions,id'
            ]
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function updateTeam($team)
    {

        DB::transaction(function () use($team){
            $team->update(['name' => $this->name,]);
            $team->headquarter()->update(['name' => $this->headquarter]);
            $team->professions()->sync($this->professions ?: []);
        });

    }
}