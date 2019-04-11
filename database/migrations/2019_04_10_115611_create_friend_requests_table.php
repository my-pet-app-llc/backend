<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('requesting_owner_id')->unsigned();
            $table->bigInteger('responding_owner_id')->unsigned();
            $table->boolean('accept')->nullable();
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
        Schema::dropIfExists('friend_requests');
    }
}
