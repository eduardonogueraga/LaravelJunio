<?php

namespace Tests\Feature\Admin;

use App\Profession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterProfessionTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    function it_loads_the_users_list_page_with_filters(){
        //Mientras que no vengan de modelos no hay comprobacion de colleciones Mira el test filterUser
        $this->get('profesiones')
            ->assertStatus(200)
            ->assertSee(trans('professions.title.index'))
            ->assertSeeInOrder(trans('professions.academic_level'))
            ->assertSeeInOrder(trans('professions.workday'));
    }

    /** @test */
    function filter_profession_by_language()
    {
        $professionWithLangauge = Profession::factory()->withLanguage()->create();
        $professionWithOutLangauge = Profession::factory()->create(['language' => '0']);

        $response = $this->get('profesiones?language=with')
        ->assertOk();

        $response->assertViewCollection('professions')
            ->contains($professionWithLangauge)
            ->notContains($professionWithOutLangauge);
    }

    /** @test */
    function filter_profession_by_vehicle()
    {
        $professionWithVehicle = Profession::factory()->withVehicle()->create();
        $professionWithOutVehicle = Profession::factory()->create(['vehicle' => '0']);

        $response = $this->get('profesiones?transport=without')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->contains($professionWithOutVehicle)
            ->notContains($professionWithVehicle);
    }

    /** @test */
    function filter_profession_by_experience()
    {
        $professionWithExperience = Profession::factory()->create(['experience' => '4']);
        $professionWithOutExperience = Profession::factory()->create(['experience' => null]);

        $response = $this->get('profesiones?experience=without')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->contains($professionWithOutExperience)
            ->notContains($professionWithExperience);
    }

    /** @test */
    function filter_professions_by_workday()
    {
        $professionJornadaCompleta = Profession::factory()->create(['workday' => 'Jornada completa']);
        $professionTemporal = Profession::factory()->create(['workday' => 'Temporal']);
        $professionIndefinido = Profession::factory()->create(['workday' => 'Indefinido']);
        $professionBeca = Profession::factory()->create(['workday' => 'Beca']);

        $response = $this->get('profesiones?workday=Temporal')
            ->assertOk();
        $response->assertViewCollection('professions')
            ->contains($professionTemporal)
            ->notContains($professionJornadaCompleta)
            ->notContains($professionIndefinido)
            ->notContains($professionBeca);
    }

    /** @test */
    function filter_profession_by_academic_level()
    {
        $profesionUniversidad = Profession::factory()->create(['academic_level' => 'Estudios universitarios']);
        $profesionSecundaria = Profession::factory()->create(['academic_level' => 'Educación secundaria']);
        $profesionPostgrado = Profession::factory()->create(['academic_level' => 'Estudios de postgrado']);
        $profesionBasica = Profession::factory()->create(['academic_level' => 'Enseñanza básica']);

        $response = $this->get('profesiones?academic_level=Estudios de postgrado')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->notContains($profesionUniversidad)
            ->notContains($profesionSecundaria)
            ->contains($profesionPostgrado)
            ->notContains($profesionBasica);
    }

    /** @test */
    function filter_professions_by_language_vehicle_and_experience()
    {
        $profession= Profession::factory()->withLanguage()->create([
            'vehicle' => '0',
            'experience' => '4'
        ]);

        $profession2 = Profession::factory()->withVehicle()->create([
            'language' => '0',
            'experience' => null
        ]);

        $profession3 = Profession::factory()
            ->withLanguage()
            ->withVehicle()
            ->create(['experience' => '1']);

        $response = $this->get('profesiones?language=without&transport=with&experience=without')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->notContains($profession)
            ->contains($profession2)
            ->notContains($profession3);
    }

    /** @test */
    function filter_professions_by_workday_and_academic_level()
    {
        $profesion1= Profession::factory()->create([
            'academic_level' => 'Estudios universitarios',
            'workday' => 'Indefinido'
        ]);

        $profesion2= Profession::factory()->create([
            'academic_level' => 'Educación secundaria',
            'workday' => 'Temporal'
        ]);

        $profesion3= Profession::factory()->create([
            'academic_level' => 'Educación secundaria',
            'workday' => 'Beca'
        ]);

        $response = $this->get('profesiones?workday=Beca&academic_level=Educación secundaria')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->notContains($profesion1)
            ->notContains($profesion2)
            ->contains($profesion3);

    }

    /** @test */
    function filter_professions_by_workday_academic_level_experience_vehicle_and_language()
    {
        $profession1 = Profession::factory()->withVehicle()->withLanguage()->create([
            'academic_level' => 'Estudios universitarios',
            'workday' => 'Indefinido',
            'experience' => '3'
        ]);

        $profession2 = Profession::factory()->create([
            'academic_level' => 'Educación secundaria',
            'workday' => 'Temporal',
            'experience' => '1',
            'vehicle' => '0',
            'language' => '0'
        ]);

        $profession3 = Profession::factory()->withLanguage()->create([
            'academic_level' => 'Educación secundaria',
            'workday' => 'Beca',
            'experience' => null,
            'vehicle' => '0'
        ]);

        $profession4 = Profession::factory()->withVehicle()->withLanguage()->create([
            'academic_level' => 'Estudios universitarios',
            'workday' => 'Temporal',
            'experience' => '2'
        ]);

        $response = $this->get('profesiones?language=with&transport=with&experience=with&workday=Temporal&academic_level=Estudios universitarios')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->notContains($profession1)
            ->notContains($profession2)
            ->notContains($profession3)
            ->contains($profession4);
    }
}