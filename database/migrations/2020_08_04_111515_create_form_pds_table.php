<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_pds', function (Blueprint $table) {
            $table->id();
            $table->string('pdname')->nullable();
            $table->string('venue')->nullable();
            $table->string('firstname');
            $table->string('surname');
            $table->string('email');
            $table->integer('seqtaId');
            $table->date('startDate')->nullable();
            $table->date('finishDate')->nullable();
            $table->time('startTime')->nullable();
            $table->time('finishTime')->nullable();
            $table->integer('hours')->nullable();
            $table->text('outcome')->nullable();
            $table->string('status');
            $table->integer('currentpage');
            $table->integer('relief')->nullable();
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
        Schema::dropIfExists('form_pds');
    }
}
