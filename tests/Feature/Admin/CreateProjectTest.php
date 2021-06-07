<?php

namespace Tests\Feature\Admin;

use App\Team;
use App\User;
use Illuminate\Support\Carbon;
use Psy\Util\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'title' => 'Proyecto de pureba',
        'about' => 'Descripcion sobre el proyecto de pruebas',
        'budget' => 2000,
        'finish_date' => '06/10/2021',
    ];

    /** @test  */
    public function it_shows_the_create_project_page()
    {
        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);

        $this->get(route('projects.create'))
            ->assertStatus(200)
            ->assertSee('Crear nuevo proyecto')
            ->assertDontSee('Estado del proyecto')
            ->assertViewCollection('teams')
            ->contains($team);
    }


    /** @test  */
    public function it_creates_a_new_project()
    {
        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);

        $this->from(route('projects.create'))
            ->post(route('projects.store'), $this->withData([
                'teams' => [$team->id]
            ]))->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => 'Proyecto de pureba',
            'about' => 'Descripcion sobre el proyecto de pruebas',
            'budget' => 2000,
        ]);
    }

    /** @test  */
    public function the_title_is_required()
    {
        $this->isRequiredField('title');
    }


    /** @test  */
    public function the_title_format_is_valid()
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'title' => '12@#~€¬'
            ]))->assertSessionHasErrors(['title'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }


    /** @test  */
    public function the_title_is_in_range()
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'title' => 'Hola'
            ]))->assertSessionHasErrors(['title'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }

    /** @test  */
    public function the_about_field_is_required()
    {
        $this->isRequiredField('about');
    }


    /** @test  */
    public function the_about_field_is_on_range()
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'about' => \Illuminate\Support\Str::random(1200)
            ]))->assertSessionHasErrors(['about'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }


    /** @test  */
    public function the_budget_field_is_on_range()
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'budget' => 900
            ]))->assertSessionHasErrors(['budget'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'budget' => 12000
            ]))->assertSessionHasErrors(['budget'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }


    /** @test  */
    public function the_budget_field_is_required()
    {
        $this->isRequiredField('budget');
    }

    /** @test  */
    public function the_finish_date_field_is_required()
    {
        $this->isRequiredField('finish_date');
    }

    /** @test  */
    public function the_finish_date_field_is_valid()
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'finish_date' => now()->format('d-m-Y')
            ]))->assertSessionHasErrors(['finish_date'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }


    /** @test  */
    public function the_finish_date_must_be_posterior_to_start_day()
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'finish_date' => '2001-07-05'
            ]))->assertSessionHasErrors(['finish_date'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }


    /** @test  */
    public function the_teams_field_is_required()
    {
        $this->isRequiredField('teams');
    }

    /** @test  */
    public function the_team_field_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'teams' => '12,34,34'
            ]))->assertSessionHasErrors(['teams'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }


    /** @test  */
    public function the_team_must_exist_in_teams_table()
    {
        $this->handleValidationExceptions();

        $team = Team::factory()->create();

        $this->from(route('projects.create'))
            ->post(route('projects.store'),$this->withData([
                'teams' => [$team->id+999]
            ]))->assertSessionHasErrors(['teams'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }


    public function isRequiredField($field): void
    {
        $this->handleValidationExceptions();

        $this->from(route('projects.create'))
            ->post(route('projects.store'), $this->withData([
                $field => null
            ]))->assertSessionHasErrors([$field])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('projects');
    }
}