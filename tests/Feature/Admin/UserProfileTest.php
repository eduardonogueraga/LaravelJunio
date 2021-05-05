<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Skill;
use App\User;
use App\UserProfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'first_name' => 'Pepe',
        'last_name' => 'Pérez',
        'email' => 'pepe@mail.es',
        'bio' => 'Programador de Laravel y Vue.js',
        'twitter' => 'https://twitter.com/pepe',
    ];

    /** @test */
    function a_user_can_edit_its_profile()
    {
        $user = User::factory()->create();

        $newProfession = Profession::factory()->create();

        // $this->actingAs($user);

        $skill1= Skill::factory()->create();
        $skill2= Skill::factory()->create();

        $user->skills()->attach([$skill1->id, $skill2->id]);

        $response = $this->get('/editar-perfil/');
        $response->assertStatus(200);

        $response = $this->put('editar-perfil/', [
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe',
            'profession_id' => $newProfession->id,
        ]);

        $response->assertRedirect('/editar-perfil/');

        $this->assertDatabaseHas('users', [
            'first_name' => 'Pepe',
            'last_name' => 'Pérez',
            'email' => 'pepe@mail.es',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/pepe',
            'profession_id' => $newProfession->id,
        ]);

        $this->assertDatabaseCount('skill_user', 2);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skill1->id,
        ]);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skill2->id,
        ]);
    }

    /** @test */
    function the_user_cannot_change_its_skills()
    {
        $user = User::factory()->create();
        $oldSkill = Skill::factory()->create();
        $newSkill = Skill::factory()->create();
        $user->skills()->attach([$oldSkill->id]);

        $response = $this->put('/editar-perfil/', $this->withData(['skills' => [$newSkill->id],]));

        $response->assertRedirect();

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
    function the_user_cannot_change_its_role()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->put('/editar-perfil/', $this->withData([
            'role' => 'admin',
        ]));

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'user',
        ]);
    }

    /** @test */
    function the_user_cannot_change_its_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('old123'),
        ]);

        $response = $this->put('/editar-perfil/', $this->withData([
            'email' => 'pepe@mail.es',
            'password' => 'new456',
        ]));

        $response->assertRedirect();

        $this->assertCredentials([
            'email' => 'pepe@mail.es',
            'password' => 'old123',
        ]);
    }
}
