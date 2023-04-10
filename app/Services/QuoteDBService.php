<?php

namespace App\Services;

use App\Models\V1\Channel;
use App\Models\V1\Quote;
use Illuminate\Support\Facades\DB;

class QuoteDBService
{
    public function create(Channel $channel, array $quote): Quote
    {

    }

    public function getQuoteById(Channel $channel, string $id): Quote
    {

    }

    public function getRandom(int $number=5): Quote
    {

    }

    public function getRandomByChannel(Channel $channel, int $number=5)
    {
        $quotes = $channel
            ->quotes()
            ->orderBy(DB::Raw('RAND()'))
            ->take($number)
            ->get();

        if ($quotes === null) {
            throw new Exception('Could not get quotes.');
        }

        return $quotes;
    }
}
