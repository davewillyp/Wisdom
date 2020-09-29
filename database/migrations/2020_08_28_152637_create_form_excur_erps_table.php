<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurErpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_erps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->text('erpText')->nullable();
            $table->foreignId('form_excur_file_id')->nullable();
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
        Schema::dropIfExists('form_excur_erps');
    }
}
