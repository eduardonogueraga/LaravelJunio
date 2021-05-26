<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTeamsTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Alpacas Manuel',
        'headquarter' => 'Bogota',
        'professions' => '',
    ];

    /** @test  */
    public function it_creates_a_new_team()
    {
        $profession = Profession::factory()->create();

       $this->from('/equipos/crear')
           ->post('/equipos/', $this->withData([
               'professions' => [$profession->id]
           ]))->assertRedirect('/equipos/');

       $this->assertDatabaseHas('teams', [
           'name' => 'Alpacas Manuel'
       ]);

       $team = Team::whereName('Alpacas Manuel')->first();

       $this->assertDatabaseHas('profession_team', [
           'team_id' => $team->id,
           'profession_id' => $profession->id
       ]);

       $this->assertDatabaseCount('headquarters',1);

       $this->assertDatabaseHas('headquarters', [
           'name' => 'Bogota'
       ]);
    }

    /** @test  */
    function the_name_field_is_required()
    {
        $this->handleValidationExceptions();
        $this->from(route('teams.create'))
            ->post(route('teams.store'), $this->withData([
                'name' => null
            ]))->assertSessionHasErrors(['name'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('profession_team');
    }

    /** @test  */
    function the_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'name' => '1234@#~€¬',
            ])))->assertSessionHasErrors(['name'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('profession_team');
    }

    /** @test  */
    function the_headquarters_field_is_required()
    {
        $this->handleValidationExceptions();

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'headquarter' => null,
            ])))->assertSessionHasErrors(['headquarter'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('profession_team');
    }

    /** @test  */
    function the_headquarters_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'headquarter' => '@#~€¬123avc',
            ])))->assertSessionHasErrors(['headquarter'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('profession_team');
    }

    /** @test  */
    function the_headquarters_field_must_be_unique()
    {
        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        $team->headquarter()->update([
            'name' => 'Sede 123',
        ]);

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'headquarter' => 'Sede 123',
            ])))->assertSessionHasErrors(['headquarter'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseMissing('teams', [
            'name' => 'Alpacas Manuel'
        ]);

        $this->assertDatabaseCount('headquarters', 1);
    }

    /** @test  */
    function the_profession_field_is_optional()
    {
        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'professions' => []
            ])))
            ->assertRedirect(route('teams.index'));

        $this->assertDatabaseCount('teams', 1);
        $this->assertDatabaseCount('headquarters', 1);
        $this->assertDatabaseCount('profession_team', 0);
    }

    /** @test  */
    function the_profession_field_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'professions' => 'String',
            ])))->assertSessionHasErrors(['professions'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('headquarters');
        $this->assertDatabaseEmpty('profession_team');
    }

    /** @test  */
    function the_professions_must_be_valid()
    {
        $this->handleValidationExceptions();
        $profession = Profession::factory()->create();

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'professions' => [$profession->id, $profession->id+999],
            ])))->assertSessionHasErrors(['professions'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('headquarters');
        $this->assertDatabaseEmpty('profession_team');
    }
}