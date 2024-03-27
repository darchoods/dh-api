<?php

namespace App\Http\Controllers\V1\QDB;

use App\Http\Controllers\BaseApiController;
use App\Services\ChannelService;
use App\Services\QuoteDBService;
use Illuminate\Http\Request;

class QuoteController extends BaseApiController
{
    public function getChannels(Request $request) 
    {
        $channels = (new ChannelService)
            ->getAll();

        return $this->sendOK([
            'channels' => $channels,
        ]);
    }

    public function create(Request $request)
    {
        $channel = (new ChannelService)
            ->getChannel($request->get('channel'));
        if ($channel === null) {
            return $this->sendError('Can\'t find channel', 404);
        }

        $quote = (new QuoteDBService)
            ->create($channel, [
                'content' => $request->get('quote'),
                'author_id' => $request->get('author'),
            ]);

        return $this->sendOK([
            'quote' => $quote,
        ]);
    }

    public function findRandom(Request $request)
    {
        $channel = $request->get('channel', null);
        if ($channel !== null) {
            $channel = (new ChannelService)
                ->getChannel($request->get('channel'));
            if ($channel === null) {
                return $this->sendError('Can\'t find channel', 404);
            }
            $quote = (new QuoteDBService)
                ->getRandomByChannel($channel, 1);

            return $this->sendOK([
                'quote' => $quote->first()->transform(),
            ]);

        } else {
            $quotes = (new QuoteDBService)
                ->getRandom($request->get('number', 5));

            return $this->sendOK([
                'quotes' => $quotes->map->transform(),
            ]);
        }
    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
