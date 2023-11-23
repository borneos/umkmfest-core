<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('slug');
            $table->longText('description');
            $table->string('presenter_name');
            $table->string('presenter_position');
            $table->longText('presenter_image');
            $table->longText('presenter_image_additional');
            $table->longText('image');
            $table->longText('image_additional');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->string('location_link');
            $table->boolean('status');
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
        Schema::dropIfExists('events');
    }
}
