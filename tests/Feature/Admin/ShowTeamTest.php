<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_a_404_error_if_the_team_is_not_found()
    {
        $this->withExceptionHandling();
        $this->get(route('teams.show', ['team' => 999]))
            ->assertStatus(404)
            ->assertSee('Página no encontrada');
    }

    /** @test */
    public function it_loads_the_team_details_page()
    {
        $profession = Profession::factory()->create();
        $team = Team::factory()->create();
        $team->professions()->attach($profession->id);

        $this->get(route('teams.show', ['team' => $team->id]))
            ->assertOk()
            ->assertSeeInOrder([
                $team->name,
                $profession->title
            ]);
    }
}