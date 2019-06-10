<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->unique(['email', 'deleted_at']);
        });
        Schema::table('owners', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('pets', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('connects', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('event_invites', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('friends', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('friend_requests', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('support_chat_rooms', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('owners', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('pets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('connects', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('event_invites', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('friends', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('friend_requests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('support_chat_rooms', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
