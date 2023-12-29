<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('public', function () {
    return true;
});

Broadcast::channel('private.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('presence', function (User $user) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('chatwhisp', function ($user) {
    return Auth::check();
});
// Broadcast::channel('chats', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('chats.{recId}', function (User $user, int $recId) {
    // return true;
    return ['id' => $user->id, 'name' => $user->name];
});
