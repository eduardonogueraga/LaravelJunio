<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListTeamsTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
    function it_shows_the_team_list()
    {
        Team::factory()->create(['name' => 'Alpacas Manuel']);
        Team::factory()->create(['name' => 'Zaraiche Shops']);
        Team::factory()->create(['name' => 'Desatascos Teruel']);

        $response = $this->get('/equipos/')->assertOk();
        $response->assertSeeInOrder([
            'Alpacas Manuel',
            'Desatascos Teruel',
            'Zaraiche Shops'
        ]);

        $this->assertNotRepeatedQueries();
    }

    /** @test */
    function it_shows_a_default_message_if_the_team_list_is_empty()
    {
        $this->get('/equipos?empty')->assertOk()
            ->assertSee('No hay equipos para listar');
    }

    /** @test */
    function it_paginates_the_teams()
    {
        Team::factory()->create(['name' => 'Alpacas Manuel']);
        Team::factory()->create(['name' => 'Zaraiche Shops']);
        Team::factory()->times(20)->create();
        Team::factory()->create(['name' => 'Benancio Cortesistos']);

        $this->get(route('teams.index'))
            ->assertOk()
            ->assertSee([
                'Alpacas Manuel',
                'Benancio Cortesistos'
            ])->assertDontSee('Zaraiche Shops');

        $this->get(route('teams.index', ['page' => 2]))
            ->assertOk()
            ->assertSee([
                'Zaraiche Shops'
            ])->assertDontSee([
                'Alpacas Manuel',
                'Benancio Cortesistos'
            ]);
    }

    /** @test */
    function teams_are_ordered_by_name()
    {
        $team1 = Team::factory()->create(['name' => 'Destileria Juanfran']);
        $team2 = Team::factory()->create(['name' => 'Alpacas Manu']);
        $team3 = Team::factory()->create(['name' => 'Zanguangos INC']);

        $response = $this->get(route('teams.index', ['order' => 'nombre_empresa']));
        $response->assertOk();
        $response->assertSeeInOrder([
            $team2->name,
            $team1->name,
            $team3->name,
        ]);

        $response = $this->get(route('teams.index', ['order' => 'nombre_empresa-desc']));
        $response->assertOk();
        $response->assertSeeInOrder([
            $team3->name,
            $team1->name,
            $team2->name,
        ]);

    }

    /** @test */
    function teams_are_ordered_by_workers()
    {
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();
        $team3 = Team::factory()->create();

        User::factory()->times(3)->create(['team_id' => $team1->id]);
        User::factory()->times(2)->create(['team_id' => $team2->id]);
        User::factory()->times(5)->create(['team_id' => $team3->id]);

        $response = $this->get(route('teams.index', ['order' => 'trabajadores']));
        $response->assertOk();
        $response->assertSeeInOrder([
            $team2->name,
            $team1->name,
            $team3->name,
        ]);

        $response = $this->get(route('teams.index', ['order' => 'trabajadores-desc']));
        $response->assertOk();
        $response->assertSeeInOrder([
            $team3->name,
            $team1->name,
            $team2->name
        ]);

    }

    /** @test */
    function teams_are_ordered_by_professions()
    {
        $profession1 = Profession::factory()->create();
        $profession2 = Profession::factory()->create();
        $profession3 = Profession::factory()->create();

        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        $team1->professions()->attach([$profession1->id, $profession2->id, $profession3->id]);
        $team2->professions()->attach([$profession2->id]);

        $response = $this->get(route('teams.index', ['order' => 'numero_profesiones']));
        $response->assertOk();
        $response->assertSeeInOrder([
            $team2->name,
            $team1->name
        ]);

        $response = $this->get(route('teams.index', ['order' => 'numero_profesiones-desc']));
        $response->assertOk();
        $response->assertSeeInOrder([
            $team1->name,
            $team2->name
        ]);
    }
}