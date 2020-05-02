<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('status')->nullable();
            $table->integer('round_duration')->default(8);
            $table->timestamp('round_ends_at')->nullable();
            $table->integer('min_vote_percentage')->default(60);
            $table->string('spy_uuid')->nullable();
            $table->string('place_label')->nullable();
            $table->integer('points_for_spy_win')->default(5);
            $table->integer('points_for_spy_loss')->default(1);
            $table->integer('points_for_spy_found')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
