<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimerDatabase extends Migration
{
    public function up()
    {
        Schema::create('timers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('commercial_id');
            $table->timestamp('start');
            $table->unsignedBigInteger('count')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('timers');
    }
}
