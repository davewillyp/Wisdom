<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFormExcurRisks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_excur_risks', function ($table) {
            $table->integer('coc')->nullable();      
            $table->integer('ep')->nullable();      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_excur_risks', function ($table) {
            $table->dropColumn('coc')->nullable();      
            $table->dropColumn('ep')->nullable();      
        });
    }
}
