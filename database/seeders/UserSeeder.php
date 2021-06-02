<?php

namespace Database\Seeders;

use App\Country;
use App\User;
use App\Profession;
use App\Skill;
use App\Team;
use App\Login;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private $professions;
    private $skills;
    private $teams;
    private $countries;

    public function run()
    {
        $this->fetchRelations();

        $this->createAdmin();

        foreach (range(1, 199) as $i) {
            $this->createRandomUser();
        }
    }

    public function fetchRelations()
    {
        $this->professions = Profession::all();
        $this->skills = Skill::all();
        $this->teams = Team::all();
        $this->countries = Country::all();
    }

    public function createAdmin()
    {
        $admin = User::create([
            'team_id' => $this->teams->firstWhere('name', 'IES Ingeniero')->id,
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'is_leader' => true,
            'created_at' => now(),
            'active' => true,
        ]);

        $admin->skills()->attach($this->skills);

        $admin->profile()->create([
            'bio' => 'Programador',
            'profession_id' => $this->professions->where('title', 'Desarrollador Back-End')->first()->id,
            'telephone' => '555 626-34-2223'
        ]);

        $admin->address()->create([
            'region' => 'Murcia',
            'city' => 'Alhama',
            'street' => 'Calle de los carmelitas',
            'country_id' => Country::create(['name' => 'Spain'])->id,
            'zipcode' => '30300',
        ]);

    }

    public function createRandomUser()
    {
        $user = User::factory()->create([
            'team_id' => rand(0, 2) ? null : $this->teams->random()->id,
            'active' => rand(0, 4) ? true : false,
            'created_at' => now()->subDays(rand(1, 90)),
        ]);

        if($user->team->id)
        {
            if(empty($user->team->users->id)) {
                $user->update(['is_leader' => 1,]);
            }
        }

        $user->skills()->attach($this->skills->random(rand(0, 7)));

        $user->profile()->update([
            'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
        ]);

        $user->address()->update([ //Remplaza el defecto por los validos de la base de datos
            'country_id' => $this->countries->random()->id,
        ]);

        if(!rand(0, 2)) {
            $user->profile()->update([
                'twitter' => null,
            ]);
        }

        Login::factory()->times(rand(1, 10))->create([
            'user_id' => $user->id,
        ]);
    }

}
