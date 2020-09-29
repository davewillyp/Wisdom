<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPdReliefdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_pd_reliefdates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_pd_id');
            $table->date('startDate');
            $table->date('finishDate');
            $table->time('startTime');
            $table->time('finishTime');
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
        Schema::dropIfExists('form_pd_reliefdates');
    }
}
