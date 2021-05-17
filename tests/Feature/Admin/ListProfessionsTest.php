<?php

namespace Tests\Feature\Admin;

use App\Profession;
use League\MimeTypeDetection\GeneratedExtensionToMimeTypeMap;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListProfessionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_professions_list()
    {
        Profession::factory()->create(['title' => 'Diseñador']);
        Profession::factory()->create(['title' => 'Programador']);
        Profession::factory()->create(['title' => 'Administrador']);

        $this->get('/profesiones')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Administrador',
                'Diseñador',
                'Programador'
            ]);

        $this->assertNotRepeatedQueries();
    }

    /** @test  */
    function it_shows_a_default_message_if_the_professions_list_is_empty()
    {
        $this->get('profesiones?empty')
            ->assertStatus(200)
            ->assertSee(trans('professions.title.index'))
            ->assertSee('No hay profesiones para listar');
    }

    /** @test  */
    function it_paginates_the_professions()
    {
        Profession::factory()->create(['title' => 'Aviador']);
        Profession::factory()->create(['title' => 'Camarero']);
        Profession::factory()->times(20)->create();
        Profession::factory()->create(['title' => 'Xenomorfo']);
        Profession::factory()->create(['title' => 'Zangano']);

        $this->get('profesiones')
            ->assertSeeInOrder([
                'Aviador',
                'Camarero',
            ])->assertDontSee('Xenomorfo')
            ->assertDontSee('Zangano');

        $this->get('profesiones?page=2')
            ->assertSeeInOrder([
                'Xenomorfo',
                'Zangano'
            ])->assertDontSee('Aviador')
            ->assertDontSee('Camarero');
    }
}
