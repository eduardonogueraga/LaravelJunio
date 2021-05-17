<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\User;
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

    /** @test  */
    function professions_are_ordered_by_title()
    {
        Profession::factory()->create(['title' => 'Civil Engineer']);
        Profession::factory()->create(['title' => 'Bar tender']);
        Profession::factory()->create(['title' => 'Waiter']);

        $response = $this->get('profesiones?order=titulo')
            ->assertOk();

        $response->assertSeeInOrder([
            'Bar tender',
            'Civil Engineer',
            'Waiter'
        ]);

        $response = $this->get('profesiones?order=titulo-desc')
            ->assertOk();

        $response->assertSeeInOrder([
            'Waiter',
            'Civil Engineer',
            'Bar tender',
        ]);
    }

    /** @test  */
    function professions_are_ordered_by_workday()
    {
        Profession::factory()->create(['workday' => 'Temporal']);
        Profession::factory()->create(['workday' => 'Beca']);
        Profession::factory()->create(['workday' => 'Indefinido']);

        $response = $this->get('profesiones?order=jornada')
            ->assertOk();

        $response->assertSeeInOrder([
            'Beca',
            'Indefinido',
            'Temporal'
        ]);

        $response = $this->get('profesiones?order=jornada-desc')
            ->assertOk();

        $response->assertSeeInOrder([
            'Temporal',
            'Indefinido',
            'Beca',
        ]);

    }

    /** @test  */
    function profession_are_ordered_by_academic_level()
    {
        Profession::factory()->create(['academic_level' => 'Estudios universitarios']);
        Profession::factory()->create(['academic_level' => 'Estudios de postgrado']);
        Profession::factory()->create(['academic_level' => 'Educación secundaria']);

        $response = $this->get('profesiones?order=nivel')
            ->assertOk();

        $response->assertSeeInOrder([
            'Educación secundaria',
            'Estudios de postgrado',
            'Estudios universitarios'
        ]);

        $response = $this->get('profesiones?order=nivel-desc')
            ->assertOk();

        $response->assertSeeInOrder([
            'Estudios universitarios',
            'Estudios de postgrado',
            'Educación secundaria',
        ]);
    }

    /** @test  */
    function profession_are_ordered_by_salary()
    {
        Profession::factory()->create(['salary' => '10000']);
        Profession::factory()->create(['salary' => '7000']);
        Profession::factory()->create(['salary' => '52000']);

        $response = $this->get('profesiones?order=salario')
            ->assertOk();

        $response->assertSeeInOrder([
            number_format(7000, 0, ',', '.'),
            number_format(10000, 0, ',', '.'),
            number_format(52000, 0, ',', '.'),
        ]);

        $response = $this->get('profesiones?order=salario-desc')
            ->assertOk();

        $response->assertSeeInOrder([
            number_format(52000, 0, ',', '.'),
            number_format(10000, 0, ',', '.'),
            number_format(7000, 0, ',', '.'),
        ]);
    }

    /** @test  */
    function profession_are_ordered_by_profile_count()
    {
        $professionMax = Profession::factory()->create();
        $professionMin = Profession::factory()->create();
        $professionZero = Profession::factory()->create();

        $user1 = User::factory()->create();
        $user1->profile()->update(['profession_id' => $professionMax->id]);

        $user2 = User::factory()->create();
        $user2->profile()->update(['profession_id' => $professionMax->id]);

        $user3 = User::factory()->create();
        $user3->profile()->update(['profession_id' => $professionMin->id]);

        $response = $this->get('profesiones?order=perfiles')
            ->assertOk();

        $response->assertSeeInOrder([
            $professionZero->profiles_count,
            $professionMin->profiles_count,
            $professionMax->profiles_count,
        ]);

        $response = $this->get('profesiones?order=perfiles-desc')
            ->assertOk();

        $response->assertSeeInOrder([
            $professionMax->profiles_count,
            $professionMin->profiles_count,
            $professionZero->profiles_count,
        ]);
    }

    /** @test */
    function invalid_order_query_data_is_ignored_and_default_order_is_used_instead()
    {
        Profession::factory()->create(['title' => 'Civil Engineer','workday' => 'Temporal']);
        Profession::factory()->create(['title' => 'Bar tender','workday' => 'Beca']);
        Profession::factory()->create(['title' => 'Waiter','workday' => 'Indefinido']);

        $response = $this->get('profesiones?order=titulo-descent')
            ->assertOk();

        $response->assertSeeInOrder([
            'Bar tender',
            'Civil Engineer',
            'Waiter'
        ]);

        $response = $this->get('profesiones?order=jornade')
            ->assertOk();

        $response->assertSeeInOrder([
            'Bar tender',
            'Civil Engineer',
            'Waiter'
        ]);

    }
}
