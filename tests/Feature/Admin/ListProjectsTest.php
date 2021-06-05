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

}