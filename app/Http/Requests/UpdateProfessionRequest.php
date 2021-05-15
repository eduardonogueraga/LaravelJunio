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
            'salary' => ['required', 'numeric'],
            'workday' => 'required|in:' . implode(',', trans('professions.workday')),
            'language' => 'required|in:0,1',
            'vehicle' => 'required|in:0,1',
            'academic_level' => 'required|in:' . implode(',', trans('professions.academic_level')),
            'experience' => 'nullable',
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