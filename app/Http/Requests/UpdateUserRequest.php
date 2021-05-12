<?php

namespace App\Http\Requests;

use App\Country;
use App\Profession;
use App\Role;
use App\Rules\UserRequestRules;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    use UserRequestRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'password' => '',
            'region' => 'required',
            'city' => 'required',
            'street' => 'required',
            'country_id' => [
                'required',
                Rule::exists('countries', 'id')->whereNull('deleted_at'),
            ],
            'zipcode' => 'required',
            'team_id' => [
                'nullable',
                Rule::exists('teams', 'id'),
            ],
            'bio' => 'required',
            'twitter' => ['nullable', 'present', 'url'],
            'role' => [
                Rule::in(Role::getList())
            ],
            'profession_id' => [
                'nullable', 'present',
                'required_without:other_profession',
                Rule::exists('professions', 'id')->whereNull('deleted_at'),
                $this->onlyWithoutField('other_profession', 'profesion')
            ],
            'other_profession' => [
                'nullable',
                Rule::unique('professions', 'title')->whereNull('deleted_at'),
            ],
            'skills' => [
                'array',
                Rule::exists('skills', 'id')
            ],
            'state' => [
                Rule::in(['active', 'inactive']),
            ]
        ];
    }

    public function updateUser(User $user)
    {
        $user->fill([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'team_id' => $this->team_id,
            'email' => $this->email,
            'role' => $this->role,
            'state' => $this->state,
        ]);

        if ($this->password != null) {
            $user->password = bcrypt($this->password);
        }

        $user->save();

        $user->profile->update([
            'bio' => $this->bio,
            'twitter' => $this->twitter,
            'profession_id' => $this->selectProfession(),
        ]);

        $user->address()->update([
            'region' => $this->region,
            'city' => $this->city,
            'street' => $this->street,
            'country_id' => $this->country_id,
            'zipcode' => $this->zipcode,
        ]);

        $user->skills()->sync($this->skills ?: []);
    }

}
