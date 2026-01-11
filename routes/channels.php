<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['api', 'auth:api']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

Broadcast::channel('telegram.chat.{chatId}', function ($user, $chatId) {
    return $user->hasPermissionTo('telegram-chats.manage');
});

Broadcast::channel('telegram.chats.list', function ($user) {
    return $user->hasPermissionTo('telegram-chats.manage');
});
