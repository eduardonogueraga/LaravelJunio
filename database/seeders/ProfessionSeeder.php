<?php

namespace Database\Seeders;

use App\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {

        Profession::create([
            'title' => 'Desarrollador Back-End',
            'salary' => '14000',
            'workday' => 'Jornada completa',
            'language' => '0',
            'vehicle' => '0',
            'academic_level' => 'Educación secundaria',
            'experience' => '2',
        ]);

        Profession::create([
            'title' => 'Desarrollador Front-End',
            'salary' => '12000',
            'workday' => 'Jornada completa',
            'language' => '0',
            'vehicle' => '0',
            'academic_level' => 'Educación secundaria',
            'experience' => '5',
        ]);

        Profession::create([
            'title' => 'Diseñador web',
            'salary' => '15000',
            'workday' => 'Jornada completa',
            'language' => '0',
            'vehicle' => '0',
            'academic_level' => 'Educación secundaria',
            'experience' => '3',
        ]);

        Profession::factory()->times(200)->create();
    }
}
