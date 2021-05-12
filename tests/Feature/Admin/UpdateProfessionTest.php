<?php

namespace Tests\Feature\Admin;

use App\Profession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProfessionTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'title' => 'Desarrollador web',
    ];

    /** @test  */
    function its_can_update_a_profession()
    {
        $profession = Profession::factory()->create();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData())
            ->assertRedirect('profesiones');

        $this->assertDatabaseHas('professions', [
            'title' => 'Desarrollador web',
        ]);
    }

    /** @test */
    function the_profession_field_is_required()
    {
        $profession = Profession::factory()->create([
            'title' => 'Old profession',
        ]);
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", [
                'title' => null,
            ])->assertSessionHasErrors(['title'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

       $this->assertDatabaseHas('professions', [
           'title' => 'Old profession',
       ]);
    }


    /** @test  */
    function the_profession_title_must_be_valid()
    {
        $this->handleValidationExceptions();

        $profession = Profession::factory()->create([
            'title' => 'Chef',
        ]);
        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'title' => 'Invalid-@#~€-Profession',
            ]))
            ->assertSessionHasErrors(['title'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions',[
            'title' => 'Chef',
        ]);
    }

}