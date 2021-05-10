<?php


namespace App\Rules;


use App\Profession;

trait UserRequestRules
{
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