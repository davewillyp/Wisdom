<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuicklinksAddTermColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quicklinks', function ($table) {
            $table->integer('year');      
            $table->date('expire')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quicklinks', function ($table) {
            $table->dropColumn('term'); 
            $table->dropColumn('expire');
        });
    }
}
