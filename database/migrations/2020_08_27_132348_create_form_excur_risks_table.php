<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->integer('firstaid');
            $table->text('firstaidStaff')->nullable();
            $table->integer('bronze');            
            $table->text('bronzeStaff')->nullable();
            $table->text('attending')->nullable();
            $table->text('instructors')->nullable();
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
        Schema::dropIfExists('form_excur_risks');
    }
}
