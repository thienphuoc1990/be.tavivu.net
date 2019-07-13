<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->integer('type')->nullable();
            $table->string('time_range');
            $table->string('vehicle')->nullable();
            $table->string('from_place')->nullable();
            $table->string('to_place')->nullable();
            $table->text('tour_attractions')->nullable();
            $table->text('tour_policies')->nullable();
            $table->integer('is_hot')->nullable();
            $table->integer('is_coming')->nullable();
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
        Schema::dropIfExists('tours');
    }
}
