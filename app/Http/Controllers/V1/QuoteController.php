<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\BaseApiController;
use App\Services\ChannelService;
use App\Services\QuoteDBService;
use Illuminate\Http\Request;

class QuoteController extends BaseApiController
{
    public function create(QuoteCreateRequest $request)
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
    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }
}
