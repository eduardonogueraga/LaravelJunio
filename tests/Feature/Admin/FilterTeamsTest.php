<?php

namespace Tests\Feature\Admin;

use App\Headquarter;
use App\Profession;
use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_teams_list_page_with_filters()
    {
        $headSinTeam = Headquarter::factory()->create();

        $professionInTeams = Profession::factory()->create(['title' => 'Alpaquero']);
        $professionInTeams_2 = Profession::factory()->create(['title' => 'Zirguero']);
        $professionNotInTeams = Profession::factory()->create();

        $team = Team::factory()->create();

        $team->professions()->attach([$professionInTeams->id, $professionInTeams_2->id]);

       $response = $this->get(route('teams.index'))
            ->assertSeeInOrder(
                trans('teams.filters.workers'),
                trans('teams.filters.professions')
            )->assertSee('Listado de equipos');

         $response->assertSeeInOrder([$professionInTeams->title, $professionInTeams_2->title]);

        $response->assertViewCollection('professions') //Solo muestra las que tienen un team
            ->contains($professionInTeams)
            ->notContains($professionNotInTeams)
            ->contains($professionInTeams_2);

        $response->assertViewCollection('headquarters')
            ->notContains($headSinTeam)
            ->contains($team->headquarter()->first());

        /* //Version pura
         * $response->assertViewHas('headquarters', function ($headquarters) use ($team){
            return $headquarters->contains($team->headquarter()->first());
        });*/

    }

    /** @test */
    function filter_teams_by_having_workers()
    {
        $teamWithUsers = Team::factory()->create();
        $teamWithoutUsers = Team::factory()->create();

        User::factory()->create(['team_id' => $teamWithUsers->id]);

        $response = $this->get(route('teams.index', ['worker' => 'with']));
        $response->assertOk();
        $response->assertViewCollection('teams')->contains($teamWithUsers)->notContains($teamWithoutUsers);

        $response = $this->get(route('teams.index', ['worker' => 'without']));
        $response->assertOk();
        $response->assertViewCollection('teams')->contains($teamWithoutUsers)->notContains($teamWithUsers);
    }

    /** @test */
    function filter_teams_by_having_professions()
    {
        $profession1 = Profession::factory()->create();
        $profession2 = Profession::factory()->create();

        $teamWithProfessions= Team::factory()->create();
        $teamWithoutProfession = Team::factory()->create();
        $teamWithProfessions->professions()->attach([$profession1->id, $profession2->id]);

        $response = $this->get(route('teams.index', ['profession' => 'with']));
        $response->assertOk();
        $response->assertViewCollection('teams')
            ->contains($teamWithProfessions)->notContains($teamWithoutProfession);

        $response = $this->get(route('teams.index', ['profession' => 'without']));
        $response->assertOk();
        $response->assertViewCollection('teams')
            ->contains($teamWithoutProfession)->notContains($teamWithProfessions);

    }

    /** @test */
    function filter_teams_by_professions()
    {
        $profession1 = Profession::factory()->create();
        $profession2 = Profession::factory()->create();
        $profession3 = Profession::factory()->create();

        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        $team1->professions()->attach([$profession1->id,$profession3->id]);
        $team2->professions()->attach([$profession1->id,$profession2->id]);

        $response = $this->get("/equipos?search=&professions[]=$profession1->id&professions[]=$profession3->id");
        $response->assertOk();
        $response->assertViewCollection('teams')
            ->contains($team1)
            ->notContains($team2);
    }
    /** @test */
    function filter_teams_by_headquarters()
    {
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();

        $team1->headquarter()->update(['name' => '302 Alpacas St']);
        $team2->headquarter()->update(['name' => '756 Zorintio Av']);

        $response = $this->get(route('teams.index', ['headquarter' => '756 Zorintio Av']));
        $response->assertOk();
        $response->assertViewCollection('teams')
            ->contains($team2)
            ->notContains($team1);
    }
}