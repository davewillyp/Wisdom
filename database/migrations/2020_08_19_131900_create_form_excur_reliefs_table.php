<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurReliefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_reliefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->integer('seqtaId');
            $table->string('firstname');
            $table->string('surname');
            $table->string('email');
            $table->date('date');
            $table->string('period');
            $table->string('class');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('form_excur_reliefs');
    }
}
