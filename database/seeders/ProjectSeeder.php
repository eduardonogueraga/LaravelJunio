<?php

namespace Database\Seeders;

use App\Project;
use App\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1,100) as $i){$this->createRandomProjects();}
    }

    public function createRandomProjects()
    {
        $carbonMethod = rand(0,1) ? 'addDays' : 'subDays';

        $project = Project::factory()->create([
            'status' => rand(0,1),
            'finish_date' => now()->$carbonMethod(rand(1,90)),
        ]);

        $teams = Team::inRandomOrder()->has('users')->take(rand(2,6))->pluck('id');

        foreach ($teams as $team){
            $project->teams()->attach($team, ['is_head_team' => !rand(0,4)]); //Le añadimos un segundo param por tupla
        }


    }

}
