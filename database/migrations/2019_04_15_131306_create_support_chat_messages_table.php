<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('support_chat_room_id')->unsigned();
            $table->bigInteger('sender_id')->unsigned()->default(0);
            $table->tinyInteger('type');
            $table->boolean('is_like')->default(false);
            $table->morphs('messagable');
            $table->timestamps();

            $table->foreign('support_chat_room_id')->references('id')->on('support_chat_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_chat_messages');
    }
}
