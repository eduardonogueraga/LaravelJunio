<?php

namespace Tests\Feature\Admin;

use App\Skill;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function filter_users_by_state_active()
    {
        $activeUser = User::factory()->create();
        $inactiveUser = User::factory()->inactive()->create();

        $response = $this->get('/usuarios?state=active');

        $response->assertViewCollection('users')
            ->contains($activeUser)
            ->notContains($inactiveUser);
    }

    /** @test */
    function filter_users_by_state_inactive()
    {
        $activeUser = User::factory()->create();
        $inactiveUser = User::factory()->inactive()->create();

        $response = $this->get('/usuarios?state=inactive');

        $response->assertViewCollection('users')
            ->contains($inactiveUser)
            ->notContains($activeUser);
    }

    /** @test */
    function filter_users_by_role_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->get('/usuarios?role=admin');

        $response->assertViewCollection('users')
            ->contains($admin)
            ->notContains($user);
    }

    /** @test */
    function filter_users_by_role_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->get('/usuarios?role=user');

        $response->assertStatus(200);

        $response->assertViewCollection('users')
            ->contains($user)
            ->notContains($admin);
    }

    /** @test */
    function filter_users_by_skills()
    {
        $php = Skill::factory()->create(['name' => 'php']);
        $css = Skill::factory()->create(['name' => 'css']);

        $backendDev = User::factory()->create();
        $backendDev->skills()->attach($php);

        $frontendDev = User::factory()->create();
        $frontendDev->skills()->attach($css);

        $fullendDev = User::factory()->create();
        $fullendDev->skills()->attach([$php->id, $css->id]);

        $response = $this->get("/usuarios?skills[0]={$php->id}&skills[1]={$css->id}");

        $response->assertStatus(200);

        $response->assertViewCollection('users')
            ->contains($fullendDev)
            ->notContains($backendDev)
            ->notContains($frontendDev);
    }

    /** @test */
    function filter_users_created_from_date()
    {
        $newestUser = User::factory()->create([
            'created_at' => '2020-10-02 12:00:00',
        ]);
        $oldestUser = User::factory()->create([
            'created_at' => '2020-09-29 12:00:00',
        ]);
        $newUser = User::factory()->create([
            'created_at' => '2020-10-01 00:00:00',
        ]);
        $oldUser = User::factory()->create([
            'created_at' => '2020-09-30 23:59:59',
        ]);

        $response = $this->get('/usuarios?from=01/10/2020');

        $response->assertOk();

        $response->assertViewCollection('users')
            ->contains($newUser)
            ->contains($newestUser)
            ->notContains($oldUser)
            ->notContains($oldestUser);
    }

    /** @test */
    function filter_users_created_to_date()
    {
        $newestUser = User::factory()->create([
            'created_at' => '2020-10-02 12:00:00',
        ]);
        $oldestUser = User::factory()->create([
            'created_at' => '2020-09-29 12:00:00',
        ]);
        $newUser = User::factory()->create([
            'created_at' => '2020-10-01 00:00:00',
        ]);
        $oldUser = User::factory()->create([
            'created_at' => '2020-09-30 23:59:59',
        ]);

        $response = $this->get('/usuarios?to=30/09/2020');

        $response->assertOk();

        $response->assertViewCollection('users')
            ->contains($oldestUser)
            ->contains($oldUser)
            ->notContains($newestUser)
            ->notContains($newUser);
    }

    /** @test */
    function filter_users_by_team_name()
    {
        $team1 = Team::factory()->create(['name' => 'Empresa Paco']);
        $team2 = Team::factory()->create(['name' => 'Desatascos Teruel']);

        $user1 = User::factory()->create(['team_id' => $team1->id]);
        $user2 = User::factory()->create(['team_id' => $team2->id]);

        $response = $this->get('usuarios?teamName=Desatascos Teruel');
        $response->assertOk();
        $response->assertViewCollection('users')
            ->contains($user2)
            ->notContains($user1);
    }


    /** @test */
    function filter_users_by_search_and_team()
    {
        $team1 = Team::factory()->create();

        $armando1 = User::factory()->create([
            'first_name' => 'Armando',
            'last_name' => 'Guerra',
            'team_id' => $team1->id,
        ]);

        $armando2 = User::factory()->create([
            'first_name' => 'Armando',
            'last_name' => 'Segura',
        ]);

        $response = $this->get('/usuarios?team=with_team&search=Armando');
        $response->assertOk();

        $response->assertViewCollection('users')
        ->contains($armando1)
        ->notContains($armando2);
    }

    /** @test */
    function filter_users_by_search_and_state()
    {
        $team = Team::factory()->create();
        $activeAntonio = User::factory()->create([
            'first_name' => 'Antonio',
            'team_id' => $team->id,
        ]);

        $inactiveAntonio = User::factory()->inactive()->create([
            'first_name' => 'Antonio',
        ]);

        $response = $this->get('usuarios?state=active&search=Antonio');
        $response->assertOk();
        $response->assertViewCollection('users')
            ->contains($activeAntonio)
            ->notContains($inactiveAntonio);
    }

    /** @test */
    function filter_users_by_search_and_state_and_team()
    {
        $team = Team::factory()->create();

        $kike = User::factory()->create([
            'first_name' => 'Kike',
            'last_name' => 'Montilla',
            'team_id'   => $team->id,
        ]);

        $kikeInactive = User::factory()->inactive()->create([
            'first_name' => 'Kike',
            'last_name' => 'Garcia',
            'team_id'   => $team->id,
        ]);

        $kikeNoTeam = User::factory()->create([
            'first_name' => 'Kike',
            'last_name' => 'Cocunero',
        ]);

        $response = $this->get('usuarios?team=with_team&state=active&search=Kike');
        $response->assertOk();
        $response->assertViewCollection('users')
            ->contains($kike)
            ->notContains($kikeInactive)
            ->notContains($kikeNoTeam);
    }

    /** @test */
    function filter_users_by_state_and_team_and_role()
    {
        $team = Team::factory()->create();

        $activeAdmin = User::factory()->create(['role' => 'admin']);
        $inactive = User::factory()->inactive()->create();

        $activeAdminTeam = User::factory()->create([
            'team_id' => $team->id,
            'role' => 'admin'
        ]);

        $response = $this->get('usuarios?team=with_team&state=active&role=admin');
        $response->assertOk();

        $response->assertViewCollection('users')
            ->contains($activeAdminTeam)
            ->notContains($activeAdmin)
            ->notContains($inactive);
    }

    /** @test */
    function filter_users_by_search_and_team_from()
    {
        self::markTestIncomplete();
        $team1 = Team::factory()->create();

        $oldWithTeam = User::factory()->create([
            'first_name' => 'Armando',
            'team_id' => $team1->id,
            'created_at' => '2019-02-30 23:59:59',
        ]);

        $oldWithoutTeam = User::factory()->create([
            'first_name' => 'Armando',
            'created_at' => '2019-02-30 23:59:59',
        ]);

        $newWithTeam = User::factory()->create([
            'first_name' => 'Armando',
            'team_id' => $team1->id,
            'created_at' => '2020-10-24 13:59:59',
        ]);

        $newWithoutTeam = User::factory()->create([
            'first_name' => 'Armando',
            'created_at' => '2020-10-24 13:59:59',
        ]);

        $otherWithTeam = User::factory()->create([
            'first_name' => 'Kike',
            'team_id' => $team1->id,
            'created_at' => '2020-12-24 13:59:59',
        ]);

        $response = $this->get('/usuarios?team=with_team&search=Armando&from=24%2F10%2F2020&to=');
        $response->assertOk();
        $response->assertViewCollection('users')
            ->contains($newWithTeam)
            ->notContains($newWithoutTeam)
            ->notContains($otherWithTeam)
            ->notContains($oldWithTeam)
            ->notContains($oldWithoutTeam);
    }

}
