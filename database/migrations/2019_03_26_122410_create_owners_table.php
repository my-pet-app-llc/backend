<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('gender', 30)->nullable();
            $table->tinyInteger('age')->nullable();
            $table->date('birthday')->nullable();
            $table->string('occupation', 255)->nullable();
            $table->string('hobbies', 255)->nullable();
            $table->string('pets_owned', 255)->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('favorite_park', 255)->nullable();
            $table->tinyInteger('signup_step')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('owners');
    }
}
