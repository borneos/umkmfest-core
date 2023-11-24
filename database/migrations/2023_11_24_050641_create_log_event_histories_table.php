<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogEventHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_event_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_id');
            $table->string('name');
            $table->string('telp')->unique();
            $table->string('email')->unique();
            $table->timestamp('checkin_at');
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
        Schema::dropIfExists('log_event_histories');
    }
}
