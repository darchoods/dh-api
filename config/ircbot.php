<?php

use App\Helpers\Irc\Bot\Client\Message;

$path = app_path('Helpers\IRC\Bot\Modules');

return [
    'servers' => [
        'default' => [
            'id' => 'dh',
            'hostname' => 'irc.darkscience.net',
            'ssl' => true,
            'port' => '6697',
            'password' => null,
        ]
    ],
    'bot' => [
        'nick' => env('BOT_NICK'),
        'name' => env('BOT_NICK'),
        'account' => env('BOT_ACCOUNT'),
        'password' => env('BOT_PASS'), // nickserv passy
        'version' => 'V4.2',

        'queue_timeout' => 1,
        'queue_buffer' => 255,

        'admin_password' => '',
        'god_mask' => 'sid3260@staff.darkscience.net',
        'command_trigger' => '>',

        'join_channels' => [
            '#bots',
            // '#cybershade',
            '#darkscience',
            // '#darchoods',
            // '#php',
            // '#chasenet',
            // '#treehouse',
        ],

        'log_file' => storage_path('taylor.log'),
        'log' => function (Message $message, $sent = false) {
            echo date('H:i:s', time()).' '.$message->raw.PHP_EOL;
            //ob_flush(); flush();
        },
    ],
    'modules' => [
        $path.'/Debug.php',
        // $path.'/Ignore.php',
        $path.'/Core.php',
        // $path.'/Darchoods.php',
        // $path.'/String.php',
        // $path.'/Docs.php',
        $path.'/QuoteDB.php',
        // $path.'/WebScrapers.php',
        // $path.'/UrlDetection.php',
    ],
    'api' => [
        'darchoods' => env('BOT_DH'),
        'forecastio' => env('BOT_FORECASTIO'),
        'imgur' => [
            'client_id' => env('BOT_IMGUR_ID'),
            'client_secret' => env('BOT_IMGUR_SECRET'),
        ],
        'wolframalpha' => env('BOT_WOLFRAM'),
        'google' => [
            'api-key' => env('BOT_GOOGLE')
        ],
    ]
];
