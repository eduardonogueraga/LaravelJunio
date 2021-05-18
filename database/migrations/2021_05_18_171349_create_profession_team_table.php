<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionTeamTable extends Migration
{
    public function up()
    {
        Schema::create('profession_team', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('profession_id');
            $table->foreign('profession_id')->references('id')->on('professions');

            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profession_team');
    }
}