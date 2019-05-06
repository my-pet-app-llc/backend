<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('requesting_owner_id')->unsigned();
            $table->bigInteger('responding_owner_id')->unsigned();
            $table->tinyInteger('matches')->default(1);
            $table->boolean('closed')->default(false);
            $table->timestamps();

            $table->foreign('requesting_owner_id')->references('id')->on('owners')->onDelete('cascade');
            $table->foreign('responding_owner_id')->references('id')->on('owners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connects');
    }
}
