<?php

namespace App\Http\Requests;

use App\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreateTeamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/'],
            'headquarter' => ['required', 'regex:/^[a-zA-Z0-9áéíóúñÑ\s]+$/', 'unique:headquarters,name'],
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

    public function createTeam()
    {
        DB::transaction(function (){
            $team = Team::create(['name' => $this->name,]);

            $team->headquarter()->create([
                'name' => $this->headquarter,
            ]);
            $team->professions()->attach($this->professions ?: []);
        });
    }
}