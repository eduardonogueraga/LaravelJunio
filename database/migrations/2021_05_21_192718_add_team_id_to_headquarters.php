<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamIdToHeadquarters extends Migration
{
    public function up()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->after('id'); //Unique es para el 1:1
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('headquarters', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });
    }
}