<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    public function it_shows_the_project_list()
    {
        Project::factory()->create(['title' => 'A Web Develop to' ]);
        Project::factory()->create(['title' => 'Zambranos project']);
        Project::factory()->create(['title' => 'Studies for web']);

        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);

        $response->assertSee(trans('projects.title.index'));

        $response->assertSeeInOrder([
            'A Web Develop to',
            'Studies for web',
            'Zambranos project'
        ]);

        $this->assertNotRepeatedQueries();

    }

    /** @test */
    public function it_shows_a_default_message_if_the_project_list_is_empty()
    {
        $this->assertDatabaseEmpty('projects');
        $response = $this->get(route('projects.index'))->assertStatus(200);
        $response->assertSee('No hay proyectos para listar');
    }

    /** @test  */
    public function it_paginate_the_projects()
    {
       Project::factory()->create(['title' => 'A incredibly web project']);
       Project::factory()->create(['title' => 'Zoom web project']);
       Project::factory()->times(20)->create();
       Project::factory()->create(['title' => 'Bar web project']);

       $response = $this->get('/proyectos?page=1')->assertStatus(200);
        $response->assertSeeInOrder(['A incredibly web project','Bar web project'])
            ->assertDontSee('Zoom web project');

        $this->get('/proyectos?page=2')
            ->assertStatus(200)
            ->assertSee('Zoom web project')
            ->assertDontSee('A incredibly web project')
            ->assertDontSee('Bar web project');

    }

    /** @test  */
    public function projects_are_ordered_by_title()
    {
        Project::factory()->create(['title' => 'Sus Project']);
        Project::factory()->create(['title' => 'A web project']);
        Project::factory()->create(['title' => 'ZZZ Propjects']);

        $this->get(route('projects.index', ['order' => 'titulo']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                'A web project',
                'Sus Project',
                'ZZZ Propjects'
            ]);

        $this->get(route('projects.index', ['order' => 'titulo-desc']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                'ZZZ Propjects',
                'Sus Project',
                'A web project',
            ]);
    }

    /** @test  */
    public function projects_are_ordered_budget()
    {
        Project::factory()->create(['budget' => 3000]);
        Project::factory()->create(['budget' => 9000]);
        Project::factory()->create(['budget' => 1500]);

        $this->get(route('projects.index', ['order' => 'presupuesto']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                1500,
                3000,
                9000,
            ]);

        $this->get(route('projects.index', ['order' => 'presupuesto-desc']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                9000,
                3000,
                1500,
            ]);

    }

    /** @test  */
    public function projects_are_ordered_status()
    {
       $current = Project::factory()->create(['status' => false]);
       $finished = Project::factory()->create(['status' => true]);

        $this->get(route('projects.index', ['order' => 'estado']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                $current->title,
                $finished->title,
            ]);

        $this->get(route('projects.index', ['order' => 'estado-desc']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                $finished->title,
                $current->title,
            ]);

    }

    /** @test  */
    public function projects_are_ordered_finish_date()
    {
        $ongoing = Project::factory()->create(['finish_date' => now()->addDays(30)]);
        $expired = Project::factory()->create(['finish_date' => now()->subDays(30)]);

        $this->get(route('projects.index', ['order' => 'plazo']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                $expired->title,
                $ongoing->title,
            ]);

        $this->get(route('projects.index', ['order' => 'plazo-desc']))
            ->assertStatus(200)
            ->assertSeeInOrder([
                $ongoing->title,
                $expired->title,
            ]);

    }

}