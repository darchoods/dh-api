<?php

namespace App\Http\Controllers\V1\NP;

use App\Http\Controllers\BaseApiController;
use App\Services\ChannelService;
use App\Services\QuoteDBService;
use Illuminate\Http\Request;
use App\Helpers\V1\GuzzleHelper;
use Illuminate\Support\Arr;

class RadioController extends BaseApiController
{
    public function web() {
        $data = $this->getData();

        $return = [
            'track' => [
                'artist' => Arr::get($data, 'icestats.source.artist'),
                'title' => Arr::get($data, 'icestats.source.title'),
                'bitrate' => Arr::get($data, 'icestats.source.audio_bitrate', 0),
            ],
            'stream' => [
                'status' => Arr::get($data, 'icestats.source.listeners', null) === null ? 'offline' : 'online',
                'listeners' => Arr::get($data, 'icestats.source.listeners', 0),
                'listener_peak' => Arr::get($data, 'icestats.source.listener_peak', 0),
                'stream_start' => \Carbon\Carbon::parse(Arr::get($data, 'icestats.source.stream_start'))->format('U'),
            ],
        ];

        return ['raw' => $return];
    }

    public function run(Request $request)
    {
        $input = $request->all();
        $data = $this->getData();

        $return = [
            'track' => [
                'artist' => Arr::get($data, 'icestats.source.artist'),
                'title' => Arr::get($data, 'icestats.source.title'),
                'bitrate' => Arr::get($data, 'icestats.source.audio_bitrate', 0),
            ],
            'stream' => [
                'status' => Arr::get($data, 'icestats.source.listeners', null) === null ? 'offline' : 'online',
                'listeners' => Arr::get($data, 'icestats.source.listeners', 0),
                'listener_peak' => Arr::get($data, 'icestats.source.listener_peak', 0),
                'stream_start' => \Carbon\Carbon::parse(Arr::get($data, 'icestats.source.stream_start'))->format('U'),
            ],
        ];

        return $this->sendResponse('ok', '200', [
            'raw' => $return,
            'return' => [
                'to' => Arr::get($input, 'message.to'),
                'method' => 'privmsg',
                'message' => Arr::get($return, 'stream.status', 'offline') === 'offline'
                    ? '[ DS Radio | Stream is Offline ]'
                    : sprintf(
                        '[ DS Radio | Track: %1$s - %2$s ]',
                        Arr::get($return, 'track.artist'),
                        Arr::get($return, 'track.title')
                    ),
            ],
	    //'data' => $data,
        ]);
    }

    private function getData()
    {
        $url = 'http://radio.darkscience.net:8000/status-json.xsl';
        // $url = 'http://192.168.1.3:8000/status-json.xsl';

        $request = (new GuzzleHelper())->request('get', $url);
        if (($request instanceof \GuzzleHttp\Psr7\Response) === false) {
            return [
                'status' => 400,
                'message' => 'Error 1: Could not query the server.',
                'request' => $request,
            ];
        }

        if ($request->getStatusCode() != '200') {
            return [
                'status' => 400,
                'message' => 'Error 2: Radio Service appears to be down, try again later.',
            ];
        }

        $body = $request->getBody();
        if (substr($body, -3) === ',}}') {
            $body = str_replace(',}}', '}}}', $body);
        }

        $json = json_decode($body, true);
        if (($jsonError = json_last_error()) !== JSON_ERROR_NONE) {
            return [
                'status' => 400,
                'message' => 'Error 3: Json Parsing Failed... ' . $jsonError,
                //'body' => $body,
            ];
        }

        return $json; 
    }
}