<?php

namespace App\Services;

use App\Models\V1\Channel;

class ChannelService
{
    public function getChannel($channel)
    {
        return Channel::firstOrCreate([
            'channel' => $channel
        ]);
    }

    public function getAll()
    {
        return Channel::where('quote_count', '>', 0)
            ->orderBy('quote_count', 'desc')
            ->get();
    }
}
