<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDutiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duties', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreignId('period_id');                     
            $table->foreignId('dutylocation_id');            
            $table->integer('day');            
            $table->string('name');
            $table->integer('updated_by');
            $table->timestamps();

            $table->unique(['period_id', 'dutylocation_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duties');
    }
}
