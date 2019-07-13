<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTourOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tour_id');
            $table->integer('tour_detail_id');
            $table->integer('tickets');
            $table->integer('kid_tickets')->nullable();
            $table->integer('baby_tickets')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('tour_orders');
    }
}
