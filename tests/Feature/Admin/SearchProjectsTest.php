<?php

namespace Tests\Feature\Admin;

use App\Project;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
     function search_by_title()
    {
        Project::factory()->create(['title' => 'Projectus tactics']);
        Project::factory()->create(['title' => 'A web project']);
        Project::factory()->create(['title' => 'Proyecto táctico']);

        $this->get('/proyectos?search=Proyecto táctico')
            ->assertStatus(200)
            ->assertSee('Proyecto táctico');
    }

    /** @test */
     function partial_search_by_title()
    {
        Project::factory()->create(['title' => 'Proyectus tactics']);
        Project::factory()->create(['title' => 'A web project']);
        Project::factory()->create(['title' => 'Proyecto táctico']);

        $this->get('/proyectos?search=Proy')
            ->assertStatus(200)
            ->assertSeeInOrder(['Proyecto táctico',
                'Proyectus tactics']);
    }

    /** @test */
    function search_by_about_field()
    {
        $project1 = Project::factory()->create([
            'about' => 'Proyecto sobre como doblar correctamente una camisa'
        ]);

        $project2 = Project::factory()->create([
            'about' => 'Pequeño proyecto sobre el analisis de datos en una aplicacion aplicacional'
        ]);

        $this->get(route('projects.index', ['search' => 'Proyecto sobre como doblar correctamente una camisa']))
            ->assertStatus(200)
            ->assertSee($project1->title)
            ->assertDontSee($project2->title);
    }

    /** @test */
    function partial_search_by_about_field()
    {
        $project1 = Project::factory()->create([
            'about' => 'Proyecto sobre como doblar correctamente una camisa'
        ]);

        $project2 = Project::factory()->create([
            'about' => 'Proyecto sobre el analisis de datos en una aplicacion aplicacional'
        ]);

        $this->get(route('projects.index', ['search' => 'Proyecto sobre']))
            ->assertStatus(200)
            ->assertSee($project1->title)
            ->assertSee($project2->title);
    }

    /** @test */
    function search_by_main_team_title()
    {
        $teamA = Team::factory()->create(['name' => 'EquipoA']);
        $teamB = Team::factory()->create(['name' => 'EquipoB']);
        $teamC = Team::factory()->create(['name' => 'EquipoC']);

        $project = Project::factory()->create();
        $project2 = Project::factory()->create();

        $project->teams()->attach([$teamA->id], ['is_head_team'=> 1]);
        $project->teams()->attach([$teamB->id, $teamC->id]);

        $this->get(route('projects.index', ['search' => 'EquipoA']))
            ->assertStatus(200)
            ->assertSee($project->title)
            ->assertDontSee($project2->title);

        $this->get(route('projects.index', ['search' => 'EquipoB']))
            ->assertStatus(200)
            ->assertDontSee($project->title);

    }

    /** @test */
    function partial_search_by_main_team_title()
    {
        $teamA = Team::factory()->create(['name' => 'EquipoA']);
        $teamB = Team::factory()->create(['name' => 'EquipoB']);
        $teamC = Team::factory()->create(['name' => 'EquipoC']);

        $project = Project::factory()->create();
        $project2 = Project::factory()->create();

        $project->teams()->attach([$teamA->id], ['is_head_team'=> 1]);
        $project->teams()->attach([$teamB->id, $teamC->id]);

        $this->get(route('projects.index', ['search' => 'Equi']))
            ->assertStatus(200)
            ->assertSee($project->title)
            ->assertDontSee($project2->title);

    }

}