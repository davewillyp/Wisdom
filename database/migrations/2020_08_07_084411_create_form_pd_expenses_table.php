<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPdExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_pd_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_pd_id');
            $table->integer('fee');
            $table->float('amount')->nullable();
            $table->integer('invoiced')->nullable();
            $table->integer('before')->nullable();
            $table->text('claim')->nullable();
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
        Schema::dropIfExists('form_pd_expenses');
    }
}
