<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallpassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hallpasses', function (Blueprint $table) {
            $table->id();
            $table->integer('staffId');
            $table->integer('studentId');
            $table->integer('class');
            $table->integer('hallpass_action_id');
            $table->date('date');
            $table->time('startTime');
            $table->time('endTime')->nullable();
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
        Schema::dropIfExists('hallpasses');
    }
}
