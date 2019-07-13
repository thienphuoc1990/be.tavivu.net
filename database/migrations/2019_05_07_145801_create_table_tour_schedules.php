<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTourSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tour_id');
            $table->string('short_title');
            $table->string('title');
            $table->text('content');
            $table->integer('index')->nullable();
            $table->integer('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_schedules');
    }
}
