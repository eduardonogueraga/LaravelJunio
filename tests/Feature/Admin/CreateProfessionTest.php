<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProfessionTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'title' => 'Desarrollador web',
    ];

    /** @test */
     function its_creates_a_new_profession()
    {
     $this->from('profesiones/create')
         ->post('profesiones/', $this->withData([
             'title' => 'Mecánico'
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
}