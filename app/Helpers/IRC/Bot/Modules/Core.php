<?php

namespace App\Helpers\IRC\Bot\Modules;

use App\Helpers\IRC\Bot\Client\Command;
use App\Helpers\IRC\Bot\Client\Message;

// join channel on invite
Message::listen('invite', function (Message $message) {
    return Message::join($message->params[1]);
});

// if i get kicked, rejoin the channel
Message::listen('kick', function ($message) {
    if ($message->params[1] == \Config::get('ircbot.bot.nick')) {
        return Message::join($message->params[0]);
    }
});

