<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreignId('period_id');
            $table->string('displayname');
            $table->date('date_of');
            $table->timestamps();
        });

        Schema::create('bookings_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id');
            $table->foreignId('bookingitem_id');
            $table->timestamps();

            $table->unique(['booking_id', 'bookingitem_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
