<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLogGameHistoriesChangeWinsAtCompleteAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_game_histories', function (Blueprint $table) {
            $table->timestamp('wins_at')->nullable()->change();
            $table->timestamp('complete_at')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_game_histories', function (Blueprint $table) {
        });
    }
}
