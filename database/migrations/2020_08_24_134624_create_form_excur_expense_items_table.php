<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormExcurExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_excur_expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_excur_id');
            $table->string('expense');
            $table->string('cheque');
            $table->float('amount');
            $table->date('required');
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
        Schema::dropIfExists('form_excur_expense_items');
    }
}
