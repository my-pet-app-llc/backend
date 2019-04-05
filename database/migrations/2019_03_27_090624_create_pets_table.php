<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('owner_id')->unsigned();
            $table->string('name', 255)->nullable();
            $table->string('gender', 40)->nullable();
            $table->string('size', 10)->nullable();
            $table->string('primary_breed', 100)->nullable();
            $table->string('secondary_breed', 100)->nullable();
            $table->tinyInteger('age')->nullable();
            $table->string('profile_picture')->nullable();
            $table->tinyInteger('friendliness')->nullable();
            $table->tinyInteger('activity_level')->nullable();
            $table->tinyInteger('noise_level')->nullable();
            $table->tinyInteger('odebience_level')->nullable();
            $table->tinyInteger('fetchability')->nullable();
            $table->tinyInteger('swimability')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('like', 255)->nullable();
            $table->string('dislike', 255)->nullable();
            $table->string('favorite_toys', 255)->nullable();
            $table->string('fears', 255)->nullable();
            $table->string('favorite_places', 255)->nullable();
            $table->boolean('spayed')->default(false);
            $table->date('birthday')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pets');
    }
}
