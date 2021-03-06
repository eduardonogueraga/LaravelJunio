<?php

namespace Database\Factories;

use App\Profession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProfessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->jobTitle,
            'salary' => $this->faker->randomFloat('2', '8000', '21000'),
            'workday' => Arr::random($this->getWorkday()),
            'language' => $this->faker->boolean,
            'vehicle' => $this->faker->boolean,
            'academic_level' => Arr::random($this->getAcademicLevel()),
            'experience' => rand(0,1)?$this->faker->numberBetween(1,6): null,
        ];
    }

    public function withLanguage()
    {
        return $this->state(function ($faker) {
            return [
                'language' => '1'
            ];
        });
    }

    public function withVehicle()
    {
        return $this->state(function ($faker) {
            return [
                'vehicle' => '1'
            ];
        });
    }


    public function getWorkday()
    {
        return ['Jornada completa', 'Media jornada', 'Temporal', 'Indefinido', 'Beca'];
    }

    public function getAcademicLevel()
    {
        return ['Estudios universitarios', 'Educación secundaria', 'Estudios de postgrado', 'Enseñanza básica'];
    }
}
