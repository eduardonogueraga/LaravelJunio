<?php

namespace Tests\Feature;

use App\Profession;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sends_a_team_to_the_trash()
    {
        $team = Team::factory()->create();
        $teamTrashed = Team::factory()->create();

        $this->patch("/equipos/{$teamTrashed->id}/papelera")
            ->assertRedirect('/equipos/papelera');

        $this->assertSoftDeleted('teams',[
            'id' => $teamTrashed->id,
            ]);

        $this->get('/equipos/')
            ->assertOk()
            ->assertSee($team->name)
            ->assertDontSee($teamTrashed->name)
            ->assertViewCollection('teams')
            ->contains($team)
            ->notContains($teamTrashed);

        $this->get('/equipos/papelera')
            ->assertOk()
            ->assertViewCollection('teams')
            ->contains($teamTrashed)
            ->notContains($team);

    }

    /** @test */
    function it_completely_deletes_a_team()
    {
        $profession = Profession::factory()->create();

        $team = Team::factory()->create([
            'deleted_at'=> now(),
        ]);

        $team->professions()->attach($profession->id);

        $this->delete("/equipos/{$team->id}")->assertRedirect('/equipos/papelera');

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('profession_team');
    }

    /** @test */
    function it_cannot_delete_a_team_that_is_not_in_the_trash()
    {
        $this->withExceptionHandling();
        $teamNotTrashed = Team::factory()->create(['deleted_at' => null]);

        $this->delete("/equipos/{$teamNotTrashed->id}")->assertStatus(404);

        $this->assertDatabaseHas('teams', [
            'id' => $teamNotTrashed->id,
            'deleted_at' => null,
        ]);

    }

    /** @test */
    function it_cannot_delete_a_team_with_user_on_it()
    {
        $this->withExceptionHandling();
        $team = Team::factory()->create();
        $user = User::factory()->create(['team_id' => $team->id]);

        $this->delete("/equipos/{$team->id}")->assertStatus(404);

        $this->assertDatabaseHas('teams', [
           'id' => $team->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'team_id' => $team->id,
        ]);

    }

}