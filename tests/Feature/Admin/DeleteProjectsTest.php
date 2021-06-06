<?php

namespace Tests\Feature\Admin;

use App\Project;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_a_project()
    {
        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);

        $project = Project::factory()->create();
        $project->teams()->attach($team->id);

        $this->delete(route('projects.destroy', ['project' => $project]))
            ->assertStatus(302)
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseEmpty('projects');

        $this->assertDatabaseHas('teams', [
            'name' => $team->name,
        ]);

        $this->assertDatabaseCount('users', 3);
    }
}