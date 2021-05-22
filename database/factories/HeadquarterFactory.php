<?php

namespace Database\Factories;

use App\Headquarter;
use Illuminate\Database\Eloquent\Factories\Factory;

class HeadquarterFactory extends Factory
{
    protected $model = Headquarter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->streetAddress,
        ];
    }
}
