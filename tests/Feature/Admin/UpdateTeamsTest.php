<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTeamsTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Alpacas Manuel',
        'headquarters' => ['Bogota'],
        'professions' => '',
    ];

    /** @test  */
    public function it_updates_a_team()
    {
        $profession1 = Profession::factory()->create();
        $profession2 = Profession::factory()->create();
        $newProfession = Profession::factory()->create();

        $oldTeam = Team::factory()->create();
        $oldTeam->professions()->attach([$profession1->id, $profession2->id]);

        User::factory()->create(['team_id' => $oldTeam->id]);

        $this->from(route('teams.edit', ['team' => $oldTeam]))
            ->put(route('teams.update', ['team' => $oldTeam]), $this->withData([
                'professions' => [$profession1->id, $newProfession->id]
            ]))->assertRedirect(route('teams.show', ['team' => $oldTeam->id]));

        $this->assertDatabaseHas('teams', [
            'name' => 'Alpacas Manuel'
        ]);

        $this->assertDatabaseHas('headquarters', [
            'name' => 'Bogota',
        ]);

        $this->assertDatabaseHas('profession_team', [
            'team_id' => $oldTeam->id,
            'profession_id' => $profession1->id,
        ]);

        $this->assertDatabaseHas('profession_team', [
            'team_id' => $oldTeam->id,
            'profession_id' => $newProfession->id,
        ]);

        $this->assertDatabaseMissing('profession_team', [
            'team_id' => $oldTeam->id,
            'profession_id' => $profession2->id,
        ]);

        $this->assertDatabaseHas('headquarters', ['name' => 'Bogota']);
    }

    /** @test  */
    function the_name_field_is_required()
    {
        $this->handleValidationExceptions();
        $team = Team::factory()->create();

        $this->from(route('teams.edit', ['team' => $team]))
            ->put(route('teams.update', ['team' => $team]), $this->withData([
                'name' => null,
            ]))->assertSessionHasErrors(['name'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('teams', [
            'name' => $team->name
        ]);
    }

    /** @test  */
    function the_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $team = Team::factory()->create();

        $this->from(route('teams.edit', compact('team')))
            ->put(route('teams.update', compact('team')), $this->withData([
                'name' => '@#~€€~#@',
            ]))->assertSessionHasErrors(['name'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('teams', [
            'name' => $team->name,
        ]);

    }

    /** @test  */
    function the_headquarters_field_is_required()
    {
        $this->handleValidationExceptions();
        $team = Team::factory()->create();

        $this->from(route('teams.edit', compact('team')))
            ->put(route('teams.update', compact('team')), $this->withData([
                'headquarters' => null,
            ]))->assertSessionHasErrors(['headquarters.*'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('headquarters', [
            'name' => $team->headquarters[0]->name, //Como metodo() trae la relacion como ref el array
        ]);
    }

    /** @test  */
    function the_headquarters_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $team = Team::factory()->create();

        $this->from(route('teams.edit', compact('team')))
            ->put(route('teams.update', compact('team')), $this->withData([
                'headquarters' => '#€~#€~#@',
            ]))->assertSessionHasErrors(['headquarters.*'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('headquarters', [
            'name' => $team->headquarters[0]->name
        ]);

    }

    /** @test  */
    function the_profession_field_is_optional()
    {
        $profession1 = Profession::factory()->create();
        $profession2 = Profession::factory()->create();

        $team = Team::factory()->create();
        $team->professions()->attach([$profession1->id, $profession2->id]);

        $this->from(route('teams.edit', compact('team')))
            ->put(route('teams.update', compact('team')), $this->withData([
                'professions' => [],
            ]))->assertRedirect(route('teams.show', compact('team')));

        $this->assertDatabaseEmpty('profession_team');

        $this->assertDatabaseMissing('profession_team', [
            'team_id' => $team->id,
            'profession_id' => $profession1->id,
        ]);
    }

    /** @test  */
    function the_profession_field_must_be_an_array()
    {
        $this->handleValidationExceptions();
        $profession = Profession::factory()->create();
        $team = Team::factory()->create();
        $team->professions()->attach([$profession->id]);

        $this->from(route('teams.edit', compact('team')))
            ->put(route('teams.update', compact('team')), $this->withData([
                'professions' => 'String'
            ]))->assertSessionHasErrors(['professions'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('profession_team', [
            'team_id' => $team->id,
            'profession_id' => $profession->id,
        ]);
    }

    /** @test  */
    function the_professions_must_be_valid()
    {
        $this->handleValidationExceptions();
        $profession = Profession::factory()->create();
        $team = Team::factory()->create();
        $team->professions()->attach([$profession->id]);

        $this->from(route('teams.edit', compact('team')))
            ->put(route('teams.update', compact('team')), $this->withData([
                'professions' => [$profession->id+999]
            ]))->assertSessionHasErrors(['professions'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseHas('profession_team',[
            'team_id' => $team->id,
            'profession_id' => $profession->id
        ]);
    }

}