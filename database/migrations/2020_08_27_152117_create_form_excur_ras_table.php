<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurRasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_ras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->string('activity');
            $table->string('hazard');
            $table->integer('risk');
            $table->text('control');
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
        Schema::dropIfExists('form_excur_ras');
    }
}
