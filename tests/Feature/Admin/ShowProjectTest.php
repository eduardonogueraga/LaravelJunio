<?php

namespace Tests\Feature\Admin;

use App\Project;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_a_404_error_if_the_project_is_not_found(){

        $this->withExceptionHandling();

        $this->get(route('projects.show', ['project' => 999]))
            ->assertStatus(404)
            ->assertSee('Página no encontrada');
    }

    /** @test */
    public function it_loads_the_team_details_page(){


        $team = Team::factory()->create();
        $user = User::factory()->create(['team_id' => $team->id]);
        $project = Project::factory()->create();
        $project->teams()->attach($team->id, ['is_head_team' => 1]);

        $this->get(route('projects.show', ['project' => $project]))
            ->assertStatus(200)
            ->assertSeeInOrder([
                $project->title,
                $project->budget,
                $project->status,
                $project->description,
                $team->title,
                $team->title,
                $user->first_name,
                $user->last_name,
                $project->finish_date->format('d-m-Y')
            ]);
    }

}