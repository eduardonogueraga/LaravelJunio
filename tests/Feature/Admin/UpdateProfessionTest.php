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
        'salary' => '15000',
        'workday' => 'Jornada completa',
        'language' => 0,
        'vehicle' => 0,
        'academic_level' => 'Educación secundaria',
        'experience' => '3',
    ];

    /** @test  */
    function its_can_update_a_profession()
    {
        $profession = Profession::factory()->create();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData())
            ->assertRedirect("profesiones/{$profession->id}/show");

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

    /** @test  */
    function the_salary_field_is_required()
    {
        $profession = Profession::factory()->create();

        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'salary' => null,
            ]))->assertSessionHasErrors(['salary'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'salary' => $profession->salary,
        ]);
    }

    /** @test */
    function the_salary_field_must_be_numeric()
    {
        $profession = Profession::factory()->create();

        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'salary' => 'Volvamos al trueque',
            ]))->assertSessionHasErrors(['salary'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'salary' => $profession->salary,
        ]);
    }

    /** @test */
    function the_workday_field_is_required()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'workday' => null,
            ]))->assertSessionHasErrors(['workday'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'workday' => $profession->workday,
        ]);
    }

    /** @test */
    function the_workday_field_must_be_valid()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'workday' => 'Jornada laboral de media hora'
            ]))->assertSessionHasErrors(['workday'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'workday' => $profession->workday,
        ]);
    }

    /** @test */
    function the_language_field_is_required()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'language' => null,
            ]))->assertSessionHasErrors(['language'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'language' => $profession->language
        ]);
    }

    /** @test */
    function the_language_field_must_be_valid()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'language' => '999',
            ]))->assertSessionHasErrors(['language'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'language' => $profession->language
        ]);
    }

    /** @test */
    function the_vehicle_field_is_required()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'vehicle' => null,
            ]))->assertSessionHasErrors(['vehicle'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'vehicle' => $profession->vehicle
        ]);
    }

    /** @test */
    function the_vehicle_field_must_be_valid()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'vehicle' => '999',
            ]))->assertSessionHasErrors(['vehicle'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'vehicle' => $profession->vehicle
        ]);
    }

    /** @test */
    function the_academic_level_field_is_required()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'academic_level' => null,
            ]))->assertSessionHasErrors(['academic_level'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'academic_level' => $profession->academic_level,
        ]);
    }

    /** @test */
    function the_academic_level_field_must_be_valid()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'academic_level' => 'La universidad de la vida',
            ]))->assertSessionHasErrors(['academic_level'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'title' => $profession->title,
            'academic_level' => $profession->academic_level,
        ]);
    }

    /** @test */
    function the_experience_field_is_optional()
    {
        $profession = Profession::factory()->create();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'experience' => null,
            ]))->assertRedirect("profesiones/{$profession->id}/show");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'experience' => null,
        ]);
    }

    /** @test */
    function the_experience_field_must_be_valid()
    {
        $profession = Profession::factory()->create();
        $this->handleValidationExceptions();

        $this->from("profesiones/{$profession->id}/editar")
            ->put("profesiones/{$profession->id}", $this->withData([
                'experience' => '0',
            ]))->assertSessionHasErrors(['experience'])
            ->assertRedirect("profesiones/{$profession->id}/editar");

        $this->assertDatabaseHas('professions', [
            'id' => $profession->id,
            'experience' => $profession->experience
        ]);
    }
}