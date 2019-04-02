<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetChatRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pet_chat_room', function (Blueprint $table) {
            $table->bigInteger('pet_id')->unsigned();
            $table->bigInteger('chat_room_id')->unsigned();
            $table->boolean('is_read')->default(false);

            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pet_chat_room');
    }
}
