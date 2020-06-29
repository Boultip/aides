<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallForProjectsPerimetersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('call_for_projects_perimeters')) {
            Schema::create('call_for_projects_perimeters', function (Blueprint $table)
            {
                $table->increments('id');
                $table->integer('perimeter_id')->unsigned()->index();
                $table->integer('call_for_project_id')->unsigned()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_for_projects_perimeters');
    }
}
