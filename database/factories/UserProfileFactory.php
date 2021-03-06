<?php

namespace Database\Factories;

use App\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bio' => $this->faker->paragraph,
            'twitter' => 'https://twitter.com/'. $this->faker->userName,
            'telephone' => $this->faker->phoneNumber
        ];
    }
}
