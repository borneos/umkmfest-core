<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLogEventHistoriesChangeTelpEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_event_histories', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropUnique(['telp']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_event_histories', function (Blueprint $table) {
            //
        });
    }
}
