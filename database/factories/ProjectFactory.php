<?php

namespace Database\Factories;

use App\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {


        return [
            'title' => $this->developFaker() . ' '. $this->faker->jobTitle,
            'about' => $this->faker->paragraph(10),
            'budget' => $this->faker->randomFloat(2, 1000, 10000),
        ];
    }

    public function developFaker()
    {
        $develops = [
            'Aplication to',
            'Studies for',
            'A web develop to',
            'Develop project for',
            'Analitics project to',
            'Web aplication to',
            'Data research for',
        ];

        return $develops[rand(0, count($develops)-1)];
    }
}
