<?php

namespace Tests\Feature\Admin;

use App\Team;
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
}