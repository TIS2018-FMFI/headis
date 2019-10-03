<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('challenge_id')->unsigned();
            $table->integer('date_id')->unsigned();
            $table->foreign('challenge_id')->references('id')->on('challenges');
            $table->foreign('date_id')->references('id')->on('dates');
            $table->boolean('confirmed')->nullable();
            $table->string('type')->default('normal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
