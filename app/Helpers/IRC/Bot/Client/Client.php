<?php

namespace App\Helpers\IRC\Bot\Client;

use App\Helpers\IRC\Bot\Client\Command;
use App\Helpers\IRC\Bot\Client\Message;
use App\Helpers\IRC\Bot\Client\Socket;
use Illuminate\Support\Arr;
use Cache;
use Config;
use Event;

class Client extends BaseHelper
{

    private $socket = null;
    private $config = [];
    protected $sender = [];

    public function __construct($config)
    {
        echo '---------------------------------------------------------'.PHP_EOL;
        echo 'Bot started at '.date('jS F H:ia').PHP_EOL;
        echo '---------------------------------------------------------'.PHP_EOL;

        $this->config = [];

        $configFile = sprintf('ircbot.servers.%s', $config);
        $this->config['server'] = Config::get($configFile, false);
        if ($this->config['server'] === false) {
            die('Server Config Not Found: '.$configFile);
        } else {
            echo 'Loaded Server Config'.PHP_EOL;
        }

        $this->config['bot'] = Config::get('ircbot.bot', false);
        if ($this->config['bot'] === false) {
            die('Taylor\'s Config Not Found');
        } else {
            echo 'Loaded Bot Config'.PHP_EOL;
            dump($this->config['bot']);
        }

        $this->socket = new Socket($this->config);
        $this->identifyMe();
        $this->setupCommands();

        $this->main();

        $this->socket = null; // Close socket

        // Cache::forget('taylor.functions');
        // Cache::forget('taylor.phpfuncs');
    }

    protected function identifyMe()
    {
        $this->sender = Sender::makeUser(Arr::get($this->config, 'bot.nick'));
        Message::user(Arr::get($this->config, 'bot.name'), Arr::get($this->config, 'bot.name'))
            ->send($this->socket);
        Message::nick(Arr::get($this->config, 'bot.nick'))
            ->send($this->socket);

        // Wait for the end of the MOTD
        while ($message = $this->read()) {
            if ($message->command == Message::RPL_ENDOFMOTD) {
                break;
            }
        }

        // Identify with NickServ
        if (Arr::get($this->config, 'bot.password', false) !== false) {
            Message::privmsg(
                'NickServ',
                sprintf(
                    'IDENTIFY %s %s',
                    Arr::get($this->config, 'bot.account'),
                    Arr::get($this->config, 'bot.password')
                )
            )->send($this->socket);
        }

        // identify as a bot
        Message::mode(Arr::get($this->config, 'bot.nick'), '+B')
            ->send($this->socket);

        // Request whois information about ourself
        Message::whois(Arr::get($this->config, 'bot.nick'))
            ->send($this->socket);

        // join set channels
        $join_channels = Arr::get($this->config, 'bot.join_channels', []);
        if (count($join_channels) > 0) {
            foreach ($join_channels as $channel) {
                Message::join($channel)->send($this->socket);
            }
        }
    }

    /**
     * Main
     *
     * The main method is the work horse of the client, it loops through all the
     * messages recieved until an ERROR is returned.
     */
    protected function main()
    {
        while ($message = $this->read()) {
            //echo '--- Fireing Event: ircbot.message: '.strtolower($message->command).PHP_EOL;
            Message::sendArray(array_merge(
                Event::dispatch('ircbot.message: '.strtolower($message->command), array($message)),
                Event::dispatch('ircbot.message: *', array($message))
            ), $this->socket);

            /**if ($message->command == Message::RPL_WHOISUSER) {
                if (strcasecmp($message->params[1], $this->sender->nick) == 0) {
                    $this->sender = Sender::makeUser($message->params[1], $message->params[2], $message->params[3]);
                }
            } **/

            Message::sendArray(Command::make($message)->run(), $this->socket);
        }
    }

    protected function setupCommands()
    {
        echo '---------------------------------------------------------'.PHP_EOL;
        echo 'Module Search'.PHP_EOL;
        echo '---------------------------------------------------------'.PHP_EOL;
        $modules = Config::get('ircbot.modules', []);
        if (empty($modules)) {
            echo 'No Modules Loaded..'.PHP_EOL;
            return;
        }

        foreach ($modules as $module) {
            if (\File::exists($module)) {
                echo sprintf('Loading: %s', $module).PHP_EOL;
                include_once($module);
                continue;
            }
            echo sprintf('Module not found: %s', $module).PHP_EOL;
        }
        echo '---------------------------------------------------------'.PHP_EOL;
        echo '/Module Search'.PHP_EOL;
        echo '---------------------------------------------------------'.PHP_EOL;
    }

    protected function read()
    {
        $message = null;
        do {
            if ($message = Message::parse($this->socket->read())) {
                // Log every message recieved
                Client::log($message);

                // Handle some core commands
                switch ($message->command) {
                    case 'ERROR':
                        return; // finish
                    break;

                    case 'PING':
                        Message::pong($message->params[0])->send($this->socket);
                    break;
                }
                return $message;
            }
            if ($this->socket->eof()) {
                return false;
            }
        } while (!$message);
    }

    public static function log(Message $message, $sent = false)
    {
        if (($logger = Config::get('ircbot.bot.log', false)) !== false) {
            $logger($message, $sent);
        }
    }
}
