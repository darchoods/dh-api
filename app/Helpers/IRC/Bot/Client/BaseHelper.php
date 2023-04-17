<?php

namespace App\Helpers\Irc\Bot\Client;

use App\Helpers\Irc\Bot\Client\Command;
use Config;
use Cache;

class BaseHelper
{
    public static function testForGod(Command $command)
    {
        if (Config::get('ircbot.bot.god_mask', null) === null) {
            return false;
        }

        if ($command->sender->user.'@'.$command->sender->host === Config::get('ircbot.bot.god_mask', null)) {
            return true;
        }

        return false;
    }

    public static function testForBot($clientNick)
    {
        $botsList = Cache::get('taylor::bots.list', []);

        // if botlist has something in, test to see if author is in there
        if (!empty($botsList) && in_array(strtolower($clientNick), $botsList)) {
            return true;
        }

        return false;
    }

    public static function addToCache($key, $value)
    {
        $values = Cache::get($key, []);
        $values[] = $value;
        Cache::forever($key, array_unique($values));
    }

    public static function getUserData($username)
    {
        // if we get this far, the user isnt on the list, lets see if they should be
        $client = new \GuzzleHttp\Client([
            'base_url' => 'https://www.darchoods.net/api/irc/',
            'defaults' => ['headers' => ['X-Auth-Token' => Config::get('taylor::api.darchoods')]],
            'timeout'  => 2,
        ]);

        try {
            $request = $client->post('user/view', ['body' => [
                'username' => $username
            ]]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return false;
        } catch (\InvalidArgumentException $e) {
            return false;
        } catch (ErrorException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }

        if ($request->getStatusCode() != '200') {
            return false;
        }

        return $request->json();
    }


    public static function color($msg, $color = null)
    {
        if (false) {
            return $msg;
        }

        switch($color){
            case 'white':    $return = chr(3).'00';          break;
            case 'black':    $return = chr(3).'01';          break;
            case 'navy':     $return = chr(3).'02';          break;
            case 'green':    $return = chr(3).'03';          break;
            case 'red':      $return = chr(3).'04';          break;
            case 'brown':    $return = chr(3).'05';          break;
            case 'purple':   $return = chr(3).'06';          break;
            case 'orange':   $return = chr(3).'07';          break;
            case 'yellow':   $return = chr(3).'08';          break;
            case 'lime':     $return = chr(3).'09';          break;
            case 'teal':     $return = chr(3).'10';          break;
            case 'aqua':     $return = chr(3).'11';          break;
            case 'blue':     $return = chr(3).'12';          break;
            case 'pink':     $return = chr(3).'13';          break;
            case 'dgrey':    $return = chr(3).'14';          break;
            case 'grey':     $return = chr(3).'15';          break;
            case 'rand':     $return = chr(3).rand(3, 15);   break;

            case 'normal':   $return = chr(15);              break;
            case 'bold':     $return = chr(2);               break;
            case 'underline':$return = chr(31);              break;
            default:         $return = chr(15);              break;
        }

        return $return.$msg.chr(3);
    }

}
