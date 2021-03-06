<?php

namespace Tests\Feature\Admin;

use App\Country;
use App\Profession;
use App\Skill;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'first_name' => 'Pepe',
        'last_name' => 'Pérez',
        'email' => 'pepe@mail.es',
        'password' => '123456',
        'password_confirmation' => '123456',
        'profession_id' => '',
        'other_profession' => '',
        'region' => 'Murcia',
        'city' => 'Alhama',
        'street' => 'Calle de los carmelitas',
        'country_id' => '',
        'team_id' => '',
        'zipcode' => '30300',
        'bio' => 'Programador de Laravel y Vue.js',
        'twitter' => 'https://twitter.com/pepe',
        'role' => 'user',
        'state' => 'active',
    ];

    /** @test */
    function it_loads_the_new_users_page()
    {
        $profession = Profession::factory()->create();
        $skillA = Skill::factory()->create();
        $skillB = Skill::factory()->create();

        $this->get('usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear nuevo usuario')
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            })
            ->assertViewHas('skills', function ($skills) use ($skillA, $skillB) {
                return $skills->contains($skillA) && $skills->contains($skillB);
            });
    }

    /** @test */
    function it_creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $profession = Profession::factory()->create();
        $team = Team::factory()->create();

        $skillA = Skill::factory()->create();
        $skillB = Skill::factory()->create();
        $skillC = Skill::factory()->create();

        $this->post('/usuarios/', $this->withData([
            'skills' => [$skillA->id, $skillB->id],
            'profession_id' => $profession->id,
            'team_id' => $team->id,
        ]))->assertRedirect('/usuarios');

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'team_id' => $team->id,
            'email' => 'pepe@mail.es',
            'password' => '123456',
            'role' => 'user',
            'active' => true,
        ]);

        $user = User::findByEmail('pepe@mail.es');

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe',
            'user_id' => $user->id,
            'profession_id' => $profession->id,
        ]);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillA->id
        ]);
        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillB->id
        ]);
        $this->assertDatabaseMissing('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillC->id
        ]);
    }

    /** @test */
    public function the_user_is_redirected_to_the_previous_page_when_the_validation_fails()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [])
            ->assertRedirect('usuarios/nuevo');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_first_name_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData(['first_name' => '']))
            ->assertSessionHasErrors(['first_name']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_last_name_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData(['last_name' => '']))
            ->assertSessionHasErrors(['last_name']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData(['email' => '']))
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_password_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData([
                'password' => '',
            ]))->assertSessionHasErrors(['password']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData([
                'email' => 'correo-no-valido'
            ]))->assertSessionHasErrors(['email']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();
        User::factory()->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->post('/usuarios/', $this->withData([
                'email' => 'pepe@mail.es',
            ]))->assertSessionHasErrors(['email']);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    function the_twitter_field_is_optional()
    {
        $profession = Profession::factory()->create();
        $this->post('/usuarios/', $this->withData(['twitter' => null, 'profession_id' => $profession->id]))
            ->assertRedirect('/usuarios');

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => null,
            'user_id' => User::findByEmail('pepe@mail.es')->id
        ]);
    }

    /** @test */
    function the_twitter_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios', $this->withData(
            ['twitter' => 'not-an-url-field']
        ))->assertSessionHasErrors(['twitter']);

        $this->assertDatabaseEmpty('users');

    }

    /** @test */
    function the_bio_field_is_required()
    {
        $this->handleValidationExceptions();
        $this->post('/usuarios', $this->withData([
            'bio' => '',
        ]))->assertSessionHasErrors(['bio']);

        $this->assertDatabaseEmpty('users');
    }


    /** @test */
    function at_least_one_of_both_profession_fields_is_required()
    {
        $this->handleValidationExceptions();
        $this->post('/usuarios/', $this->withData([
            'profession_id' => null,
            'other_profession' => null,
        ]))->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');;
    }

    /** @test */
    function profession_id_is_optional_if_other_profession_is_present()
    {
        $this->post('/usuarios/', $this->withData([
            'other_profession' => 'OtherProfession',
        ]))->assertRedirect('/usuarios');

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);
        $profession = Profession::where('title', 'OtherProfession')->first();
        $this->assertDatabaseHas('user_profiles', [
            'profession_id' => $profession->id,
        ]);

    }

    /** @test */
    function the_other_profession_must_be_unique()
    {
        $this->handleValidationExceptions();
        Profession::factory()->create(['title'=>'RepeatedProfession']);

        $this->post('/usuarios/', $this->withData([
            'other_profession' => 'RepeatedProfession',
        ]))->assertSessionHasErrors(['other_profession']);

        $this->assertDatabaseEmpty('users');
        $this->assertEquals(1, Profession::count());
    }


    /** @test */
    function the_profession_id_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData([
                'profession_id' => '999'
            ]))->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function only_not_deleted_professions_can_be_selected()
    {
        $this->handleValidationExceptions();

        $deletedProfession = Profession::factory()
            ->create([
                'deleted_at' => now()->format('Y-m-d'),
            ]);

        $this->post('/usuarios/', $this->withData([
                'profession_id' => $deletedProfession->id
            ]))->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_skills_fields_are_optional()
    {
        $profession = Profession::factory()->create();
        $this->post('/usuarios/', $this->withData([
            'skills' => [],
            'profession_id' => $profession->id,
        ]))->assertRedirect('/usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'pepe@mail.es',
            'role' => 'user',
        ]);

        $this->assertDatabaseEmpty('skill_user');
    }

    /** @test */
    function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData([
                'skills' => 'PHP, PS',
            ]))->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();

        $skillA = Skill::factory()->create();
        $skillB = Skill::factory()->create();

        $this->post('/usuarios/', $this->withData([
                'skills' => [$skillA->id, $skillB->id+1],
            ]))->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_role_field_is_optional()
    {
        $profession = Profession::factory()->create();
        $this->post('/usuarios/', $this->withData([
            'role' => null,
            'profession_id' => $profession->id
        ]))->assertRedirect('usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'pepe@mail.es',
            'role' => 'user',
        ]);
    }

    /** @test */
    function the_role_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->withData([
                'role' => 'role-invalid',
            ]))->assertSessionHasErrors(['role']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_state_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData([
            'state' => 'invalid_state'
        ]))->assertSessionHasErrors('state');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_state_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('/usuarios/', $this->withData([
            'state' => ''
        ]))->assertSessionHasErrors('state');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_team_is_optional()
    {
        $profession = Profession::factory()->create();

        $this->post('/usuarios/', $this->withData([
            'team_id' => null,
            'profession_id' => $profession->id
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'team_id' => null,
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);
    }

    /** @test */
    function the_team_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $team = Team::factory()->create();

        $this->post('/usuarios/', $this->withData([
            'team_id' => $team->id+2,
        ]))->assertSessionHasErrors(['team_id']);

        $this->assertDatabaseEmpty('users');
    }
}
