<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestoreTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_restore_a_trashed_team_and_his_professions()
    {

        $profession1 = Profession::factory()->create();
        $profession2 = Profession::factory()->create();

        $team = Team::factory()->create([
            'deleted_at'=> now(),
        ]);

        $team->professions()->attach([$profession1->id, $profession2->id]);

        $this->get("/equipos/{$team->id}/restore")->assertRedirect('/equipos/');

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
        ]);

        $this->assertDatabaseHas('profession_team', [
            'team_id' => $team->id,
            'profession_id' => $profession1->id,
        ]);

        $this->assertDatabaseHas('profession_team',[
            'team_id' => $team->id,
            'profession_id' => $profession2->id,
        ]);

    }

}