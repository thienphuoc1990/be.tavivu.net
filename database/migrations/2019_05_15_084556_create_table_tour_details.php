<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTourDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tour_id');
            $table->string('code')->nullable();
            $table->string('start_date');
            $table->string('flight_in');
            $table->string('flight_out');
            $table->integer('price')->nullable();
            $table->integer('kid_price')->nullable();
            $table->integer('baby_price')->nullable();
            $table->integer('single_room_price')->nullable();
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
        Schema::dropIfExists('tour_details');
    }
}
