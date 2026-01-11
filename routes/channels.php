<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('telegram.chat.{chatId}', function ($user, $chatId) {
    return $user->hasPermissionTo('telegram-chats.manage');
});

Broadcast::channel('telegram.chats.list', function ($user) {
    return $user->hasPermissionTo('telegram-chats.manage');
});
