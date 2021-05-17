<?php

namespace Tests\Feature\Admin;

use App\Profession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchProfessionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function search_by_title()
    {
        $profession1 = Profession::factory()->create(['title' => 'Camarero']);
        $profession2 = Profession::factory()->create(['title' => 'Taxista']);

        $response = $this->get('profesiones?search=Taxista')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->contains($profession2)
            ->notContains($profession1);
    }

    /** @test */
    public function partial_search_by_title()
    {
        $profession1 = Profession::factory()->create(['title' => 'Programador']);
        $profession2 = Profession::factory()->create(['title' => 'Prestidigitador']);
        $profession3 = Profession::factory()->create(['title' => 'Analista']);

        $response = $this->get('profesiones?search=Pr')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->contains($profession1)
            ->contains($profession2)
            ->notContains($profession3);
    }

    /** @test */
    public function search_by_workday()
    {
        $profession1 = Profession::factory()->create(['workday' => 'Jornada completa']);
        $profession2 = Profession::factory()->create(['workday' => 'Temporal']);
        $profession3 = Profession::factory()->create(['workday' => 'Beca']);

        $response = $this->get('profesiones?search=Beca')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->contains($profession3)
            ->notContains($profession1)
            ->notContains($profession2);
    }

    /** @test */
    public function partial_search_by_workday()
    {
        $profession1 = Profession::factory()->create(['workday' => 'Jornada completa']);
        $profession2 = Profession::factory()->create(['workday' => 'Temporal']);
        $profession3 = Profession::factory()->create(['workday' => 'Beca']);
        $profession4 = Profession::factory()->create(['workday' => 'Media jornada']);

        $response = $this->get('profesiones?search=jornad')
            ->assertOk();

        $response->assertViewCollection('professions')
            ->contains($profession1)
            ->contains($profession4)
            ->notContains($profession3)
            ->notContains($profession2);
    }
}