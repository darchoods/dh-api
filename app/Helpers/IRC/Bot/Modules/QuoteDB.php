<?php

namespace App\Helpers\IrcBot\Bot\Modules;

use App\Helpers\Irc\Bot\Client\BaseHelper;
use App\Helpers\Irc\Bot\Client\Command;
use App\Helpers\Irc\Bot\Client\Message;
use Config;
use Illuminate\Support\Arr;

$trigger = \Config::get('ircbot.bot.command_trigger', '>');

// setup a new client
$client = new \GuzzleHttp\Client([
    'base_uri' => 'https://api.darchoods.net/api/qdb/',
    'headers' => [
        'X-Auth-Token' => Config::get('ircbot.api.darchoods')
    ],
    'timeout' => 10,
]);

Command::register($trigger.'quote', function (Command $command) use ($client) {
    if (substr($command->params[0], 0, 1) == '?') {
        return Message::privmsg($command->message->channel(), 'Usage: <id number> or null(random quote)');
    }

    $quote_id = $command->params[0];
    $url = 'search/byId';
    if ($quote_id == 0 || !ctype_digit((string)$quote_id)) {
        $url = 'random';
    }

    try {
        $request = $client->post($url, ['form_params' => [
            'channel' => $command->message->channel(),
            'quote_id' => $quote_id,
        ]]);
    } catch (\GuzzleHttp\Exception\ServerException $e) {
        dump($e->getMessage());
        return Message::privmsg($command->message->channel(), BaseHelper::color('Error: Could not query the server.'));
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        dump($e->getMessage());
        return Message::privmsg($command->message->channel(), BaseHelper::color('Error: Could not query the server.'));
    }

    if ($request->getStatusCode() != '200') {
        return Message::privmsg($command->message->channel(), BaseHelper::color('Error: QDB appears to be down, Try again later.'));
    }

    $data = json_decode($request->getBody(), true);
    $quote = Arr::get($data, 'data.quote');
    if ($quote == false) {
        return Message::privmsg($command->message->channel(), BaseHelper::color('Error: Either Quote wasnt found or there are no quotes in this channel.'));
    }

    return Message::privmsg($command->message->channel(), sprintf(
        'Quote#%s: %s',
        Arr::get($quote, 'quote_id', 0),
        Arr::get($quote, 'content')
    ));
});

Command::register($trigger.'addquote', function (Command $command) use ($client) {
    if (empty($command->params[0]) || substr($command->params[0], 0, 1) == '?') {
        return Message::privmsg($command->message->channel(), 'Usage: <msg to quote>');
    }

    try {
        $request = $client->post('create', ['form_params' => [
            'channel' => $command->message->channel(),
            'author'  => $command->sender->nick,
            'quote'   => $command->text,
        ]]);
    } catch (\GuzzleHttp\Exception\ServerException $e) {
        dump($e->getMessage());
        return Message::privmsg($command->message->channel(), BaseHelper::color('Error: Could not query the server.'));
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        dump($e->getMessage());
        return Message::privmsg($command->message->channel(), BaseHelper::color('Error: Could not query the server.'));
    }
    if ($request->getStatusCode() != '200') {
        return Message::privmsg($command->message->channel(), BaseHelper::color('Error: QDB appears to be down, Try again later.'));
    }

    $data = json_decode($request->getBody(), true);

    return Message::privmsg($command->message->channel(), sprintf(
        'Thank you for your submission. Your quote has been added as number %d',
        Arr::get($data, 'data.quote.quote_id', 0)
    ));
});


