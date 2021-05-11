<?php

namespace Database\Factories;

use App\Address;
use App\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class AddressFactory extends Factory
{
    protected $model = Address::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'region' => $this->faker->state,
            'city' => $this->faker->city,
            'street' => $this->faker->streetAddress,
            //'country_id' => Country::firstOrCreate(['name' => 'Spain'])->id, //Fallo por duplicado
            'country_id' => null,
            'zipcode' => $this->faker->postcode,
        ];
    }
}
