<?php

namespace App\Http\Requests;


use App\Team;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class UpdateTeamRequest extends FormRequest
{
    public function rules()
    {
        $collection = $this->team->headquarters->pluck('id')->toArray();

        return [
            'name' => ['required', 'regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/'],
            'leader'=> ['required',
                        Rule::exists('users', 'id')
                                ->whereNull('team_id')
                        ],
            'headquarters.0' =>  ['required',
                                    'regex:/^[a-zA-Z0-9áéíóúñÑ\s]+$/',
                                    'unique:headquarters,name,'. $this->team->headquarters->first()->id
                                ],
            'headquarters.*' =>  ['nullable',
                                    'distinct',
                                    'regex:/^[a-zA-Z0-9áéíóúñÑ\s]+$/',
                                    Rule::unique('headquarters', 'name')->where(function ($query) use ($collection) {
                                    return $query->whereNotIn('id', $collection);})
                                 ],
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

    public function updateTeam(Team $team)
    {
        DB::transaction(function () use($team){
            $team->update(['name' => $this->name,]);

                $oldLeader = User::find($team->leader->id);
                $newLeader = User::find($this->leader);

                $oldLeader->update([
                    'is_leader' => 0,
                    'team_id' => null
                ]);

                $newLeader->update([
                    'is_leader' => 1,
                    'team_id' => $team->id
                ]);


            foreach ($this->headquarters as  $key => $head){
                if($head == null){continue;}
                $team->headquarters[$key]->update([
                    'name' => $head,
                    'is_central' => $key == 0, //Condicion
                ]);
            }
            $team->professions()->sync($this->professions ?: []);
        });

    }
}