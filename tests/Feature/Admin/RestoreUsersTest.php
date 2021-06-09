<?php

namespace Tests\Feature\Admin;

use App\Skill;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RestoreUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
     function it_restore_a_user_and_his_profile()
    {
        $user = User::factory()->create();
        $skill = Skill::factory()->create();
        $user->skills()->attach([$skill->id]);

        $user->profile()->delete();
        $user->delete();

        $this->get('/usuarios/'.$user->id.'/restore')
            ->assertRedirect('usuarios');

        $user->refresh();
        $this->assertFalse($user->trashed());

        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('user_profiles',[
            'user_id' => $user->id,
            'deleted_at' => null,
        ]);

        $this->assertDatabaseHas('skill_user',[
            'user_id' => $user->id,
            'skill_id' => $skill->id,
            'deleted_at' => null
        ]);

    }

    /** @test */
    function it_cannot_restore_a_non_trashed_user()
    {
        $this->withExceptionHandling();

        $user = User::factory()->create([
            'deleted_at' => null
        ]);

        $this->get('/usuarios/'.$user->id.'/restore')
            ->assertStatus(404);

    }
}