<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPdLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_pd_logistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_pd_id');
            $table->integer('car');
            $table->date('pickupDate')->nullable();
            $table->time('pickupTime')->nullable();
            $table->date('dropoffDate')->nullable();
            $table->time('dropoffTime')->nullable();
            $table->integer('accommodation');
            $table->date('arrival')->nullable();
            $table->date('departure')->nullable();
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
        Schema::dropIfExists('form_pd_logistics');
    }
}
