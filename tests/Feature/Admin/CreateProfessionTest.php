<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProfessionTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'title' => 'Desarrollador web',
        'salary' => '15000',
        'workday' => 'Jornada completa',
        'language' => 0,
        'vehicle' => 0,
        'academic_level' => 'Educación secundaria',
        'experience' => '3',
    ];

    /** @test */
     function its_creates_a_new_profession()
    {
     $this->from('profesiones/create')
         ->post('profesiones/', $this->withData([
             'title' => 'Mecánico',
         ]))->assertRedirect('profesiones');

     $this->assertDatabaseCount('professions', 1);

     $this->assertDatabaseHas('professions', [
         'title' =>  'Mecánico',
     ]);
    }

    /** @test */
    function the_profession_field_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('profesiones/create')
            ->post('profesiones/', [
                'title' => null,
            ])->assertSessionHasErrors(['title'])
            ->assertRedirect('profesiones/create');

        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_profession_title_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'title' => 'Invalid-$%&-Profession',
            ]))->assertSessionHasErrors(['title'])
            ->assertRedirect('profesiones/create');

        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_salary_field_is_required()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'salary' => null
            ]))->assertSessionHasErrors(['salary'])
            ->assertRedirect('profesiones/create');

        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_salary_field_must_be_numeric()
    {
        $this->handleValidationExceptions();

        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'salary' => 'Volvamos al trueque'
            ]))->assertSessionHasErrors(['salary'])
            ->assertRedirect('profesiones/create');

        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_workday_field_is_required()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'workday' => null
            ]))->assertSessionHasErrors(['workday'])
            ->assertRedirect('profesiones/create');

        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_workday_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'workday' => 'Jornada laboral de media hora'
            ]))->assertSessionHasErrors(['workday'])
            ->assertRedirect('profesiones/create');
        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_language_field_is_required()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'language' => null
            ]))->assertSessionHasErrors(['language'])
            ->assertRedirect('profesiones/create');
        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_language_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'language' => '999'
            ]))->assertSessionHasErrors(['language'])
            ->assertRedirect('profesiones/create');
        $this->assertDatabaseEmpty('professions');
    }


    /** @test */
    function the_vehicle_field_is_required()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'vehicle' => null
            ]))->assertSessionHasErrors(['vehicle'])
            ->assertRedirect('profesiones/create');
        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_vehicle_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'vehicle' => '999'
            ]))->assertSessionHasErrors(['vehicle'])
            ->assertRedirect('profesiones/create');
        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_academic_level_field_is_required()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'academic_level' => null
            ]))->assertSessionHasErrors(['academic_level'])
            ->assertRedirect('profesiones/create');

        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_academic_level_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'academic_level' => 'Universidad de la vida'
            ]))->assertSessionHasErrors(['academic_level'])
            ->assertRedirect('profesiones/create');
        $this->assertDatabaseEmpty('professions');
    }

    /** @test */
    function the_experience_field_is_optional()
    {
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'experience' => null
            ]))->assertRedirect('profesiones');
        $this->assertDatabaseCount('professions', 1);

        $this->assertDatabaseHas('professions', [
            'title' => 'Desarrollador web',
            'salary' => '15000',
            'workday' => 'Jornada completa',
        ]);
    }

    /** @test */
    function the_experience_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $this->from('profesiones/create')
            ->post('profesiones/', $this->withData([
                'experience' => '0'
            ]))->assertSessionHasErrors(['experience'])
            ->assertRedirect('profesiones/create');

        $this->assertDatabaseEmpty('professions');
    }

}