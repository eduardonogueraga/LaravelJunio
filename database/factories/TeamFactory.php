<?php

namespace Database\Factories;

use App\Country;
use App\Headquarter;
use App\Team;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    public function configure()
    {
        return $this->afterCreating(function ($team) {
            $team->headquarter()->save(Headquarter::factory()->make()); //Lo crea y la relacion le pone el id
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company,
        ];
    }
}
