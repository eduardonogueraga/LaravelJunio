<?php

namespace App\Http\Requests;

use App\Country;
use App\Profession;
use App\Role;
use App\Rules\UserRequestRules;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'region' => 'required',
            'city' => 'required',
            'street' => 'required',
            'country_id' => [
                'required',
                Rule::exists('countries', 'id')->whereNull('deleted_at'),
            ],
            'zipcode' => 'required',
            'bio' => 'required',
            'twitter' => ['nullable', 'present', 'url'],
            'role' => [
                'nullable',
                Rule::in(Role::getList())
            ],
            'profession_id' => [
                'nullable',
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
                Rule::in(['active', 'inactive'])
            ]
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'El campo nombre es obligatorio',
            'last_name.required' => 'El campo apellidos es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El valor introducido no es un correo electrónico válido',
            'password.required' => 'El campo contraseña es obligatorio',
            'profession_id.required_without' => 'Seleccione una  profesion o introduzca una',
        ];
    }

    public function createUser()
    {
        DB::transaction(function () {

            $user = User::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role' => $this->role ?? 'user',
                'state' => $this->state,
            ]);

            $user->profile()->create([
                'bio' => $this->bio,
                'twitter' => $this->twitter,
                'profession_id' => $this->selectProfession(),
            ]);

            $user->address()->create([
                'region' => $this->region,
                'city' => $this->city,
                'street' => $this->street,
                'country' => Country::find($this->country_id)->country,
                'zipcode' => $this->zipcode,
            ]);

            $user->skills()->attach($this->skills ?? []);

            $user->login()->create([  //login es la relacion y create el insert
                'user_id' => $user->id
            ]);
        });
    }

}
