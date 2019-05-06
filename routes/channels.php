<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('events.{id}', function ($user, $id) {
    return $user->id == $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return $user->owner->pet->chatRooms->pluck('id')->contains($id);
});

Broadcast::channel('ticket.chat.{id}', function ($user, $id) {
    $access = false;
    if($user->owner){
        if($user->owner->supportChatRooms()->where('id', $id)->first()){
            $access = true;
        }
    }else{
        $access = true;
    }

    return $access;
});
