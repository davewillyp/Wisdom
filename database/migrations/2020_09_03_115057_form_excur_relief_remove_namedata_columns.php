<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FormExcurReliefRemoveNamedataColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_excur_reliefs', function ($table) {
            $table->dropColumn('seqtaId');
            $table->dropColumn('firstname');
            $table->dropColumn('surname');
            $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_excur_reliefs', function ($table) {
            $table->integer('seqtaId');
            $table->string('firstname');
            $table->string('surname');
            $table->string('email');
        });
    }
}
