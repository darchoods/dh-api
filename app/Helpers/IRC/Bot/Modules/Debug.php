<?php

namespace App\Helpers\IRC\Bot\Modules;

use App\Helpers\IRC\Bot\Client\Command;
use App\Helpers\IRC\Bot\Client\Message;
use Config;

$trigger = \Config::get('ircbot.bot.command_trigger', '>');

Command::register($trigger.'ping', function (Command $command) {
    $response = number_format((microtime(true) - $command->time), 5, '.', '');
    return Message::privmsg($command->message->channel(), $command->sender->nick.': Pong ('.$response.' ms)');
});


Command::register($trigger.'test', function (Command $command) {
    dump(Config::get('ircbot'));
});

