<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->string('firstname');
            $table->string('surname');
            $table->string('year');
            $table->string('code');
            $table->integer('alert_medical')->nullable();
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
        Schema::dropIfExists('form_excur_students');
    }
}
