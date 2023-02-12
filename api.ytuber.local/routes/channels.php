<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\PrivateChannel;
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

//Broadcast::routes(['middleware' => ['auth:sanctum']]);

/*Broadcast::Channel('room.{room_id}', function ($user, $room_id) {
    //return (int) $user->id === (int) $room_id;
    return true;
});*/
Broadcast::channel('room.{room_id}', function ($user, $room_id) {
    if($user->rooms->contains($room_id)){
      return $user->activeAccount;
        //return true;
    }
    //return $user;
});
Broadcast::Channel('CompleteForPublic', function () {
    return true;
});


/*Broadcast::channel('room.{room_id}', function ($user, $room_id) {
    return true;
}, ['guards' => ['api']]);*/