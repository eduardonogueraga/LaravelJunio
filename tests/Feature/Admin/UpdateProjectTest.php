<?php

namespace Tests\Feature\Admin;

use App\Project;
use App\Team;
use App\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'title' => 'Proyecto de pureba',
        'about' => 'Descripcion sobre el proyecto de pruebas',
        'budget' => 2000,
        'finish_date' => '06/10/2021',
        'status' => false
    ];

    /** @test */
    public function it_shows_the_edit_project_page()
    {

        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create();
        $project->teams()->attach([$team->id]);


        $this->get(route('projects.edit', ['project' => $project]))
            ->assertStatus(200)
            ->assertSeeInOrder([
                $project->title,
                $project->about,
                $project->budget,
                $project->finish_date->format('d/m/Y')
            ])->assertSee('Estado del proyecto')
            ->assertViewCollection('teams')
            ->contains($team);
    }

    /** @test */
    function a_project_can_be_updated()
    {
        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);

        $newTeam = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $newTeam->id]);

        $project = Project::factory()->create();
        $project->teams()->attach([$team->id]);

        $this->from(route('profile.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]), $this->withData([
                'teams' => [$newTeam->id]
            ]))->assertRedirect(route('projects.show', ['project' => $project]));

        $this->assertDatabaseHas('projects', [
            'title' => 'Proyecto de pureba',
            'about' => 'Descripcion sobre el proyecto de pruebas',
            'budget' => 2000,
            'status' => false
        ]);
    }


    /** @test */
    function the_field_title_is_required()
    {
        $this->fieldIsRequired('title');
    }

    /** @test */
    function the_field_title_must_be_valid()
    {
        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create([ 'title' => 'Proyecto anterior']);
        $project->teams()->attach([$team->id]);

        $this->from(route('projects.edit', ['project'=>$project]))
            ->put(route('projects.update', ['project' => $project])
                 ,  $this->withData([
                     'title' => '|@#123ABC'
                ]))->assertSessionHasErrors(['title'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('projects', [
            'title' => 'Proyecto anterior',
        ]);
    }


    /** @test */
    function the_field_title_must_be_in_range(){
        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create([ 'title' => 'Proyecto anterior']);
        $project->teams()->attach([$team->id]);

        $this->from(route('profile.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
            $this->withData([
                'title' => 'Proyecto'
            ]))->assertSessionHasErrors(['title'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('projects', [
            'title' => 'Proyecto anterior',
        ]);
    }

    /** @test */
    function the_field_about_is_required()
    {
        $this->fieldIsRequired('about');
    }

    /** @test */
    function the_field_about_must_be_in_range(){
        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create(['about' => 'Descripcion sobre el proyecto de pruebas']);
        $project->teams()->attach([$team->id]);

        $this->from(route('profile.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                    'about' => Str::random(1200),
                ]))->assertSessionHasErrors(['about'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('projects', [
            'about' => 'Descripcion sobre el proyecto de pruebas',
        ]);
    }

    /** @test */
    function the_field_budget_is_required()
    {
        $this->fieldIsRequired('budget');
    }

    /** @test */
    function the_field_budget_must_be_in_range(){

        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create(['budget' => 4000]);
        $project->teams()->attach([$team->id]);

        $this->from(route('profile.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                    'budget' => 900,
                ]))->assertSessionHasErrors(['budget'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('projects', [
            'budget' => 4000,
        ]);

        $this->from(route('profile.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                    'budget' => 12000,
                ]))->assertSessionHasErrors(['budget'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('projects', [
            'budget' => 4000,
        ]);

    }

    /** @test */
    function the_field_budget_must_be_numeric()
    {
        $this->assertValidationFail('budget', 'String');
    }


    /** @test */
    function the_field_status_is_required()
    {
        $this->fieldIsRequired('status');
    }

    /** @test */
    function the_field_status_must_be_boolean()
    {
       $this->assertValidationFail('status', 1000);
    }

    /** @test */
    function the_field_finish_date_is_required()
    {
        $this->fieldIsRequired('finish_date');
    }

    /** @test */
    function the_field_finish_date_format_must_be_valid()
    {
        $this->assertValidationFail('finish_date', now()->format('d-m-Y'));
    }

    /** @test */
    function the_finish_date_is_not_expired_can_remains_or_prolonged()
    {
        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create(['finish_date' => now()->addDays(10)]);
        $project->teams()->attach([$team->id]);

        $this->from(route('projects.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                    'finish_date' => now()->addDays(15)->format('d/m/Y'),
                    'teams' => [$team->id]
                ]))->assertRedirect(route('projects.show', ['project' => $project]));

        $this->assertDatabaseHas('projects', [
            'finish_date' => now()->addDays(15)->startOfDay(),
        ]);

        $this->from(route('projects.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                    'finish_date' => now()->addDays(5)->format('d/m/Y'),
                    'teams' => [$team->id]
                ]))->assertRedirect(route('projects.show', ['project' => $project]));

        $this->assertDatabaseHas('projects', [
            'finish_date' => now()->addDays(5)->startOfDay(),
        ]);
    }


    /** @test */
    function the_finish_date_is_expired_can_remains_same_or_must_be_actual()
    {
        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create(['finish_date' => now()->subDays(10)]);
        $project->teams()->attach([$team->id]);

        $this->from(route('projects.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                   'finish_date' => now()->subDays(10)->format('d/m/Y'),
                    'teams' => [$team->id]
                ]))->assertRedirect(route('projects.show', ['project' => $project]));


        $this->from(route('projects.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                    'finish_date' => now()->subDays(9)->format('d/m/Y'),
                    'teams' => [$team->id]
                ]))->assertSessionHasErrors(['finish_date'])
            ->assertRedirect(url()->previous());

    }

    /** @test */
    function the_field_teams_is_required()
    {
        $this->fieldIsRequired('teams');
    }

    /** @test */
    function the_field_teams_must_be_an_array(){
        $this->assertValidationFail('teams', '[array]');
    }

    /** @test */
    function the_field_teams_must_be_exist_in_teams_table(){
        $team = Team::factory()->create();
        $this->assertValidationFail('teams', $team->id+99);
    }

    public function fieldIsRequired($field): void
    {
        $this->handleValidationExceptions();
        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create();
        $project->teams()->attach([$team->id]);

        $this->from(route('profile.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]), $this->withData([
                $field => null
            ]))->assertSessionHasErrors([$field])
            ->assertRedirect(url()->previous());


        $this->assertDatabaseHas('projects', [
            'title' => $project->title,
            'about' => $project->about,
            'budget' => $project->budget,
            'finish_date' => $project->finish_date,
            'status' => $project->status
        ]);
    }

    public function assertValidationFail($field, $request): void
    {
        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        User::factory()->times(3)->create(['team_id' => $team->id]);
        $project = Project::factory()->create();
        $project->teams()->attach([$team->id]);

        $this->from(route('projects.edit', ['project' => $project]))
            ->put(route('projects.update', ['project' => $project]),
                $this->withData([
                    $field => $request,
                ]))->assertSessionHasErrors([$field])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('projects', [
            'title' => $project->title,
            'about' => $project->about,
            'budget' => $project->budget,
            'finish_date' => $project->finish_date,
            'status' => $project->status
        ]);

    }
}