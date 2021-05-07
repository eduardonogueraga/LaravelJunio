<?php

namespace App\Http\Requests;

use App\Profession;
use App\Role;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
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


            $user->skills()->attach($this->skills ?? []);
        });
    }

    public function selectProfession()
    {
        if (isset($this->other_profession)) {
            $otherProfession = Profession::create([
                'title' => $this->other_profession,
            ]);
            return $otherProfession->id;
        }
        return $this->profession_id;
    }


    public function onlyWithoutField($field, $error)
    {
        return function ($attribute, $value, $fail) use ($field, $error) {
            if (request()->has($attribute) === request()->filled($field)) {
                return $fail('Complete solo un campo del tipo '.$error);
            }
        };
    }
}
