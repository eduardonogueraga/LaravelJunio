<?php

namespace Tests\Feature\Admin;

use App\Headquarter;
use App\Profession;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertTrue;

class CreateTeamsTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Alpacas Manuel',
        'headquarters' => ['Bogota'],
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
    function the_main_headquarter_field_is_required()
    {
        $this->handleValidationExceptions();

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'headquarters' => null,
            ])))->assertSessionHasErrors(['headquarters.0'])
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
                'headquarters' => '@#~€¬123avc',
            ])))->assertSessionHasErrors(['headquarters.*'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseEmpty('teams');
        $this->assertDatabaseEmpty('profession_team');
    }

    /** @test  */
    function the_headquarters_field_must_be_unique()
    {
        $this->handleValidationExceptions();

        $team = Team::factory()->create();
        $team->headquarters[0]->update([
            'name' => 'Sede 123',
        ]);

        $this->from(route('teams.create'))
            ->post(route('teams.store', $this->withData([
                'headquarters' => 'Sede 123',
            ])))->assertSessionHasErrors(['headquarters.*'])
            ->assertRedirect(url()->previous());

        $this->assertDatabaseMissing('teams', [
            'name' => 'Alpacas Manuel'
        ]);
        assertTrue((Headquarter::where('name','Sede 123')->count()) == 1);
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