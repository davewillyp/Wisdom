<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excurs', function (Blueprint $table) {
            $table->id();
            $table->string('excurname')->nullable();
            $table->string('firstname');
            $table->string('surname');
            $table->string('email');
            $table->integer('seqtaId');
            $table->date('startDate')->nullable();
            $table->time('startTime')->nullable();
            $table->date('finishDate')->nullable();
            $table->time('finishTime')->nullable();
            $table->text('aim')->nullable();
            $table->text('location')->nullable();
            $table->text('activities')->nullable();
            $table->integer('attending')->default(1);
            $table->integer('reliefReq')->default(0);
            $table->integer('currentpage')->default(1);
            $table->foreignId('status')->default(0);
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
        Schema::dropIfExists('form_excurs');
    }
}
