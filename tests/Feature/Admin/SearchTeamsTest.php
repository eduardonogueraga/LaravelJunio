<?php

namespace Tests\Feature\Admin;

use App\Headquarter;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
     function search_by_team_name()
    {
        $teamAlpaca = Team::factory()->create(['name' => 'Alpacas Manuel']);
        $teamJuanan = Team::factory()->create(['name' => 'Juanan Enterprises']);

        $this->get(route('teams.index', ['search' => 'Juanan Enterprises']))
            ->assertOk()
            ->assertViewCollection('teams')
            ->contains($teamJuanan)
            ->notContains($teamAlpaca);

    }

    /** @test  */
     function partial_search_by_team_name()
    {
        $teamAlpaca = Team::factory()->create(['name' => 'Alpacas Manuel']);
        $teamJuanan = Team::factory()->create(['name' => 'Juanan Enterprises']);

        $this->get(route('teams.index', ['search' => 'Jua']))
            ->assertOk()
            ->assertViewCollection('teams')
            ->contains($teamJuanan)
            ->notContains($teamAlpaca);
    }

    /** @test  */
    function search_by_headquarter_name()
    {
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        $team1->headquarter()->update([
            'name' => 'Sede Madrid',
        ]);

        $team2->headquarter()->update([
            'name' => 'Sede Barcelona',
        ]);

        $this->get(route('teams.index', ['search' => 'Sede Barcelona']))
            ->assertStatus(200)
            ->assertViewCollection('teams')
            ->contains($team2)
            ->notContains($team1);
    }

    /** @test  */
    function partial_search_by_headquarter_name()
    {
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();
        $team3 = Team::factory()->create();

        $team1->headquarter()->update([
            'name' => 'Sede Madrid',
        ]);

        $team2->headquarter()->update([
            'name' => 'Sede Barcelona',
        ]);

        $team3->headquarter()->update([
            'name' => 'Sede Badalona',
        ]);

        $this->get(route('teams.index', ['search' => 'Sede Ba']))
            ->assertStatus(200)
            ->assertViewCollection('teams')
            ->contains($team2)
            ->contains($team3)
            ->notContains($team1);
    }

}