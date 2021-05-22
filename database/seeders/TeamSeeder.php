<?php

namespace Database\Seeders;

use App\Headquarter;
use App\Profession;
use App\Team;
use App\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    protected $professions;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->fetchRelations();

        Team::factory()->create(['name' => 'IES Ingeniero']);

        foreach (range(1, 99) as $i) {$this->createRandomTeam();}
        //Team::factory()->times(99)->create();
    }

    public function fetchRelations()
    {
        $this->professions = Profession::all();
    }

    public function createRandomTeam(){
        $team = Team::factory()->create();
        $team->professions()->attach($this->professions->random(rand(0, 6)));
    }
}
