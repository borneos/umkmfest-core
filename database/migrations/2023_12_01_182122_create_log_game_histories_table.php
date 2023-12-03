<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogGameHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_game_histories', function (Blueprint $table) {
            $table->id();
            $table->string('id_event');
            $table->string('id_game');
            $table->string('name');
            $table->string('telp');
            $table->timestamp('play_date');
            $table->time('wins_at');
            $table->time('complete_at');
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
        Schema::dropIfExists('log_game_histories');
    }
}
