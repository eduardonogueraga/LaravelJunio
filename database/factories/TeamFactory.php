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

            $headquarters = []; //Array de instancias del factory

            foreach (range(1, rand(1,3)) as $i){
                array_push($headquarters, $this->getMake($i == 1));
            }
            foreach ($headquarters as $headquarter){
                $team->headquarters()->save($headquarter);
            }
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

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function getMake($central = false)
    {
        return Headquarter::factory()->make([
            'is_central' => $central,
        ]);
    }
}
