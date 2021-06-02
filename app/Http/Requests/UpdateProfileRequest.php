<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'. $this->user->id,
            'bio' => 'required|min:10|max:1000',
            'twitter' => 'nullable|url',
            'profession_id' => 'nullable|exists:professions,id',
            'telephone' => 'nullable|min:8|max:20|regex:/^[0-9+\s\-\(\)\.]+$/',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function updateProfile(User $user)
    {
        DB::transaction(function () use($user){
            $user->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
            ]);

            $user->profile->update([
                'bio' => $this->bio,
                'twitter' => $this->twitter,
                'profession_id' => $this->profession_id,
                'telephone' => $this->telephone
            ]);
        });
    }
}