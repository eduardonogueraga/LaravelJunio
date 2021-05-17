<?php

namespace Tests\Feature;

use App\Profession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowProfessionTest extends TestCase
{
    use RefreshDatabase;

    /** @test  */
    function it_displays_a_404_error_if_the_profession_is_not_found()
    {
        $this->withExceptionHandling();

        $response = $this->get("profesiones/999/show");
        $response->assertStatus(404);
        $response->assertSee('Página no encontrada');
    }

    /** @test  */
    function it_loads_the_profession_details_page()
    {
        $profession = Profession::factory()->create();
        $this->get("profesiones/{$profession->id}/show")
            ->assertStatus(200)
            ->assertSeeInOrder([
                $profession->title,
                $profession->workday,
                $profession->academic_level,
                number_format($profession->salary, 0, ',', '.'),
                $profession->language,
                $profession->vehicle,
            ]);
    }
}