<?php

namespace App\Http\Requests;

use App\Profession;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfessionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'regex:/^[a-zA-ZáéíóúñÑ\s]+$/'],
            'salary' => 'required',
            'workday' => 'required',
            'language' => 'required',
            'vehicle' => 'required',
            'academic_level' => 'required',
            'experience' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El campo titulo es obligatorio'
        ];
    }

    public function updateProfession($profession)
    {
        $profession->update([
            'title' => $this->title,
            'salary' => $this->salary,
            'workday' => $this->workday,
            'language' => $this->language,
            'vehicle' => $this->vehicle,
            'academic_level' => $this->academic_level,
            'experience' => $this->experience,
        ]);
    }

}