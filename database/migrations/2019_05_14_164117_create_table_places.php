<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePlaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('ascii_title');
            $table->integer('type');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('image')->nullable();
            $table->text('thumb')->nullable();
            $table->text('description')->nullable();
            $table->text('attractions')->nullable();
            $table->integer('is_hot')->nullable();
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
        Schema::dropIfExists('places');
    }
}
