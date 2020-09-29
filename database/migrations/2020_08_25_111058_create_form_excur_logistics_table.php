<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_logistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->integer('bus');
            $table->integer('car');
            $table->text('transport')->nullable();
            $table->integer('phone');
            $table->integer('epirb');
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
        Schema::dropIfExists('form_excur_logistics');
    }
}
