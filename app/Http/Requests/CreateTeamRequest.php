<?php

namespace App\Http\Requests;

use App\Team;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateTeamRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/'],
            'leader'=> ['required',
                        Rule::exists('users', 'id')->where(function ($query)  {
                            return $query->whereNull('team_id');})
                        ],
            'headquarters.0' =>  ['required', 'regex:/^[a-zA-Z0-9áéíóúñÑ\s]+$/', 'unique:headquarters,name'],
            'headquarters.*' =>  ['nullable', 'distinct', 'regex:/^[a-zA-Z0-9áéíóúñÑ\s]+$/', 'unique:headquarters,name'],
            'professions' => [
                'nullable',
                'array',
                'exists:professions,id'
            ]
        ];
    }

    public function messages()
    {
        return [
            'headquarters.0.required' => 'Al menos la sede central es obligatoria',
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

            if($this->leader != null){
                $user = User::find($this->leader);

                $user->update([
                    'is_leader' => 1,
                    'team_id' => $team->id
                ]);
            }

            foreach ($this->headquarters as  $key => $head){
                if($head == null){continue;}
                $team->headquarters()->create([
                    'name' => $head,
                    'is_central' => $key == 0, //Condicion
                ]);
            }
            $team->professions()->attach($this->professions ?: []);
        });
    }
}