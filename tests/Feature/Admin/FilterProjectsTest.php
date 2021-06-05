<?php

namespace Tests\Feature\Admin;

use App\Project;
use App\Team;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_projects_list_page_with_filters()
    {
        $this->get(route('projects.index'))
            ->assertStatus(200)
            ->assertSeeInOrder(
                trans('projects.filters.status'),
                trans('projects.filters.deadline')
            )->assertSee(trans('projects.title.index'));
    }
    /** @test */
    public function filter_projects_by_status()
    {
        $currentProject = Project::factory()->create(['status' => 0]);
        $finishedProject = Project::factory()->create(['status' => 1]);

        $this->get(route('projects.index', ['status' => 'finished']))
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($finishedProject)
            ->notContains($currentProject);

        $this->get('proyectos?status=ongoing')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($currentProject)
            ->notContains($finishedProject);

        //Version simple
//            ->assertViewHas('projects', function ($projects) use($currentProject) {
//            return $projects->contains($currentProject);
//        });

    }

    /** @test */
    public function filter_projects_by_deadline()
    {
        $projectInTime = Project::factory()->create([
            'finish_date' => now()->addDays(90),
        ]);

        $projectOutTime = Project::factory()->create([
            'finish_date' => now()->subDays(90),
        ]);

        $this->get('proyectos?deadline=current')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($projectInTime)
            ->notContains($projectOutTime);

        $this->get('proyectos?deadline=expired')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($projectOutTime)
            ->notContains($projectInTime);

    }

    /** @test */
    public function filter_projects_by_budget()
    {
        $expensiveProject = Project::factory()->create([
            'budget' => '9000'
        ]);

        $cheapProject = Project::factory()->create([
            'budget' => '2500'
        ]);

        $this->get('proyectos?budget=3.5')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($cheapProject)
            ->notContains($expensiveProject);

        $this->get('proyectos?budget=10')
            ->assertOk()
            ->assertViewCollection('projects')
            ->contains($cheapProject)
            ->contains($expensiveProject);
    }


    /** @test */
    public function filter_projects_by_number_of_teams()
    {
        $team1 = Team::factory()->create();
        $team2 = Team::factory()->create();
        $team3 = Team::factory()->create();

        $multiTeamProject = Project::factory()->create();
        $multiTeamProject->teams()->attach([$team1->id, $team2->id, $team3->id,]);

        $singleTeamProject = Project::factory()->create();
        $singleTeamProject->teams()->attach([$team2->id]);

        $this->get('/proyectos?teams=3')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($multiTeamProject)
            ->notContains($singleTeamProject);

        $this->get('/proyectos?teams=1')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($singleTeamProject)
            ->notContains($multiTeamProject);

    }

    /** @test */
    public function filter_projects_by_number_of_users()
    {
        $crowedTeam = Team::factory()->create();
        $tinyTeam = Team::factory()->create();

        User::factory()->times(8)->create(['team_id'=> $crowedTeam->id]);
        User::factory()->times(3)->create(['team_id'=> $tinyTeam->id]);

        $projectCrowed = Project::factory()->create();
        $projectCrowed->teams()->attach([$crowedTeam->id]);

        $tinyProject = Project::factory()->create();
        $tinyProject->teams()->attach([$tinyTeam->id]);

        $this->get('proyectos?workers=8')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($projectCrowed)
            ->notContains($tinyProject);

        $this->get('proyectos?workers=3')
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($tinyProject)
            ->notContains($projectCrowed);
    }

    /** @test */
    public function filter_projects_by_finish_dates()
    {
        $today = now()->format('d/m/Y');
        $twoMonthsBefore = now()->addDays(60)->format('d/m/Y');
        $twoMonthsAfter = now()->subDays(60)->format('d/m/Y');


        $closeProject = Project::factory()->create([
                'finish_date' => now()->addDays(3),
        ]);

        $farProject = Project::factory()->create([
            'finish_date' => now()->addDay(30)
        ]);

        $pastProject = Project::factory()->create([
            'finish_date' => now()->subDays(10),
        ]);

        $this->get("proyectos?from={$today}&to={$twoMonthsBefore}")
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($closeProject)
            ->contains($farProject)
            ->notContains($pastProject);

        $this->get("proyectos?from={$twoMonthsAfter}&to={$today}")
            ->assertStatus(200)
            ->assertViewCollection('projects')
            ->contains($pastProject)
            ->notContains($closeProject)
            ->notContains($farProject);
     }

}