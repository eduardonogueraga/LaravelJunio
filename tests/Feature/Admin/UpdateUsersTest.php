<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Skill;
use App\User;
use App\UserProfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'first_name' => 'Pepe',
        'last_name' => 'Pérez',
        'email' => 'pepe@mail.es',
        'password' => '123456',
        'profession_id' => '',
        'other_profession' => '',
        'region' => 'Murcia',
        'city' => 'Alhama',
        'street' => 'Calle de los carmelitas',
        'country' => 'Spain',
        'zipcode' => '30300',
        'bio' => 'Programador de Laravel y Vue.js',
        'twitter' => 'https://twitter.com/pepe',
        'role' => 'user',
        'state' => 'active',
    ];

    private function withProfession()
    {
        $profession = Profession::factory()->create();
        $this->defaultData = array_merge($this->defaultData, ['profession_id' => $profession->id]);
    }


    /** @test */
    function it_loads_the_edit_user_page()
    {
        $user = User::factory()->create();

        $this->get('/usuarios/'.$user->id.'/editar')
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar usuario')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    function it_updates_a_user()
    {
        $user = User::factory()->create();

        $oldProfession = Profession::factory()->create();
        $user->profile()->update([
            'profession_id' => $oldProfession->id,
        ]);
        $oldSkill1 = Skill::factory()->create();
        $oldSkill2 = Skill::factory()->create();
        $user->skills()->attach([$oldSkill1->id, $oldSkill2->id]);

        $newProfession = Profession::factory()->create();
        $newSkill1 = Skill::factory()->create();
        $newSkill2 = Skill::factory()->create();

        $this->put('/usuarios/'.$user->id, $this->withData([
            'role' => 'admin',
            'profession_id' => $newProfession->id,
            'skills' => [$newSkill1->id, $newSkill2->id],
            'state' => 'inactive',
        ]))->assertRedirect('/usuarios/'.$user->id);

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => '123456',
            'role' => 'admin',
            'active' => false,
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe',
            'profession_id' => $newProfession->id,
        ]);

        $this->assertDatabaseCount('skill_user', 2);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $newSkill1->id,
        ]);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $newSkill2->id,
        ]);
    }

    /** @test */
    function the_user_is_redirected_to_the_previous_page_when_the_validation_fails()
    {
           $this->handleValidationExceptions();
           $user = User::factory()->create(['first_name' => 'Pepe']);

           $this->from('/usuarios/'. $user->id .'/editar')
               ->put('/usuarios/'.$user->id, [])
               ->assertRedirect('/usuarios/'. $user->id .'/editar');
           $this->assertDatabaseHas('users', ['first_name' => 'Pepe']);
    }

    /** @test */
    function it_detaches_all_the_skills_if_none_is_checked()
    {
        $this->withProfession(); //Para que pase le añade una profesion

        $user = User::factory()->create();
        $oldSkill1 = Skill::factory()->create();
        $oldSkill2 = Skill::factory()->create();
        $user->skills()->attach([$oldSkill1->id, $oldSkill2->id]);

        $this->put('/usuarios/'.$user->id, $this->withData())
            ->assertRedirect('/usuarios/'.$user->id);

        $this->assertDatabaseEmpty('skill_user');
    }

    /** @test */
    function the_first_name_is_required()
    {
        $this->handleValidationExceptions();
        $user = User::factory()->create();

           $this->put('/usuarios/'.$user->id, $this->withData([
                'first_name' => '',
            ]))->assertSessionHasErrors(['first_name']);

        $this->assertDatabaseMissing('users', ['email' => 'pepe@mail.es']);
    }

    /** @test */
    function the_last_name_is_required()
    {
        $this->handleValidationExceptions();
        $user = User::factory()->create();

        $this->put('/usuarios/'.$user->id, $this->withData([
                'last_name' => '',
            ]))->assertSessionHasErrors(['last_name']);

        $this->assertDatabaseMissing('users', ['email' => 'pepe@mail.es']);
    }

    /** @test */
    function the_email_is_required()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();

        $this->put('/usuarios/'.$user->id, $this->withData([
                'email' => '',
            ]))->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['first_name' => 'Pepe']);
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();

        $this->put('/usuarios/'.$user->id, $this->withData([
                'email' => 'correo-no-valido',
            ]))->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['first_name' => 'Pepe']);
    }

    /** @test */
    function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();

        User::factory()->create([
            'email' => 'existing-email@mail.es'
        ]);

        $user = User::factory()->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                'email' => 'existing-email@mail.es',
            ]))->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['first_name' => 'Pepe']);
    }

    /** @test */
    function the_user_email_can_stay_the_same()
    {
        $this->withProfession();
        $user = User::factory()->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('/usuarios/'. $user->id .'/editar')
            ->put('/usuarios/'.$user->id, $this->withData([
                'email' => 'pepe@mail.es',
            ]))->assertRedirect('/usuarios/'. $user->id);

        $this->assertDatabaseHas('users', [
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
        ]);
    }

    /** @test */
    function the_password_is_optional()
    {
        $this->withProfession();
        $oldPassword = 'CLAVE ANTERIOR';
        $user = User::factory()->create([
            'password' => bcrypt($oldPassword)
        ]);

        $this->from('/usuarios/'. $user->id .'/editar')
            ->put('/usuarios/'.$user->id, $this->withData([
                'password' => '',
            ]))->assertRedirect('/usuarios/'. $user->id);

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => $oldPassword
        ]);
    }

    /** @test */
    function the_state_is_required()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();

        $this->put('/usuarios/'. $user->id, $this->withData([
                'state' => '',
            ]))->assertSessionHasErrors(['state']);
        $this->assertDatabaseMissing('users', ['first_name' => 'Pepe']);
    }

    /** @test */
    function the_state_must_be_valid()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();

        $this->put('/usuarios/'. $user->id, $this->withData([
                'state' => 'invalid-state',
            ]))->assertSessionHasErrors(['state']);

        $this->assertDatabaseMissing('users', ['first_name' => 'Pepe']);
    }

    /** @test */
    function the_twitter_field_can_be_updated_empty()
    {
        $this->withProfession();
        $user = User::factory()->create();
        $user->profile()->update([
            'twitter' => 'https://twitter.com/antonio',
        ]);

      $this->from('/usuarios/'.$user->id.'/editar')
          ->put('/usuarios/'. $user->id, $this->withData([
              'twitter' => '',
          ]))->assertRedirect('/usuarios/'.$user->id);

      $this->assertCredentials([
          'first_name' => 'Pepe',
          'last_name' => 'Pérez',
          'email' => 'pepe@mail.es',
          'password' => '123456',
      ]);

      $this->assertDatabaseHas('user_profiles', [
         'twitter' => null,
      ]);
    }

    /** @test */
    function the_twitter_must_be_updated_as_url()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();
        $user->profile()->update([
            'twitter' => 'https://twitter.com/antonio',
        ]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                'twitter' => 'not_url_field',
            ]))->assertSessionHasErrors(['twitter']);

        $this->assertDatabaseMissing('users', ['email' => 'pepe@mail.es']);

        $this->assertDatabaseHas('user_profiles',[
            'twitter' => 'https://twitter.com/antonio',
        ]);
    }

    /** @test */
    function the_bio_field_can_not_be_updated_empty()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();
        $user->profile()->update([
            'bio' => 'Testing biografy field'
        ]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                'bio' => ''
            ]))->assertSessionHasErrors(['bio']);

        $this->assertDatabaseMissing('users', ['email' => 'pepe@mail.es']);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Testing biografy field'
        ]);
    }

    /** @test */
    function the_profession_id_must_be_valid()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();
        $profession = Profession::factory()->create();

        $user->profile()->update([
            'profession_id' => $profession->id,
        ]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                'profession_id' => 'non-valid-profession'
            ]))->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'profession_id' => $profession->id,
        ]);

    }

    /** @test */
    function only_not_deleted_professions_can_be_selected()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create();
        $profession = Profession::factory()->create();
        $deletedProfession = Profession::factory()->create([
            'deleted_at' => now(),
        ]);

        $user->profile()->update([
            'profession_id' => $profession->id,
        ]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                'profession_id' => $deletedProfession->id,
            ]))->assertSessionHasErrors(['profession_id']);

    }

    /** @test */
    function the_skills_fields_are_optional()
    {
        $this->withProfession();
        $user = User::factory()->create();
        $oldSkill1 = Skill::factory()->create();
        $oldSkill2 = Skill::factory()->create();

        $user->skills()->attach([$oldSkill1->id, $oldSkill2->id]);

        $this->from('/usuarios/'.$user->id.'/editar')
            ->put('/usuarios/'.$user->id, $this->withData([
                'skills' => [],
            ]))->assertRedirect('/usuarios/'.$user->id);

        $this->assertCredentials([
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);

        $this->assertDatabaseEmpty('skill_user');

    }

    /** @test */
    function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();
        $user = User::factory()->create();
        $oldSkill = Skill::factory()->create();

        $user->skills()->attach([$oldSkill->id]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                'skills' => 'newSkill',
            ]))->assertSessionHasErrors(['skills']);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $oldSkill->id
        ]);
    }

    /** @test */
    function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();
        $user = User::factory()->create();
        $oldSkill = Skill::factory()->create();
        $newSkill = Skill::factory()->create();
        $user->skills()->attach([$oldSkill->id]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                    'skills' => [$oldSkill->id, $newSkill->id+2],
            ]))->assertSessionHasErrors(['skills']);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $oldSkill->id,
        ]);

        $this->assertDatabaseMissing('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $newSkill->id,
        ]);
    }

    /** @test */
    function the_role_field_is_required()
    {
        $this->handleValidationExceptions();

        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $this->put('/usuarios/'.$user->id, $this->withData([
                'role' => null,
            ]))->assertSessionHasErrors(['role']);

        $this->assertDatabaseHas('users', [
            'role' => 'admin',
        ]);
    }

    /** @test */
    function the_role_field_must_be_valid()
    {
        $this->handleValidationExceptions();
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->put('/usuarios/'.$user->id, $this->withData([
                'role' => 'nonValidRole',
            ]))->assertSessionHasErrors(['role']);

        $this->assertDatabaseHas('users', [
            'role' => 'admin',
        ]);
    }


}
