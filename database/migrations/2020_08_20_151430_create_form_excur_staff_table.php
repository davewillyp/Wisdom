<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->integer('seqtaId')->nullable();
            $table->string('firstname');
            $table->string('surname');
            $table->string('email')->nullable();
            $table->integer('reliefrequired')->default(0);
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
        Schema::dropIfExists('form_excur_staff');
    }
}
