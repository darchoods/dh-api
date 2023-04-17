<?php namespace api\app\Services;

use App\Models\V1\Channel;
use App\Models\V1\Quote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class QuoteDBService
{
    public function create(Channel $channel, array $quote): Quote
    {
        // increment the quote count by 1 and save
        $channel->quote_count++;
        $channel->save();

        if (($quote_id = Arr::get($quote, 'quote_id', false)) === false) {
            $quote_id = $channel->quote_count;
        }

        // save the quote
        return $channel->quote()->create([
            'quote_id'  => $quote_id,
            'author_id' => $quote['author_id'],
            'content'   => $quote['content'],
        ]);
    }

    public function getQuoteById(Channel $channel, string $id): Quote
    {
        $quote = $channel
            ->quote()
            ->where('quote_id', $id)
            ->first();

        if ($quote === null) {
            throw new Exception('Could not get quote.');
        }

        return $quote;
    }

    public function getRandomByChannel(Channel $channel, int $number=5): Collection
    {
        $quotes = $channel
            ->quote()
            ->orderBy(DB::Raw('RAND()'))
            ->take($number)
            ->get();

        if ($quotes === null) {
            throw new Exception('Could not get quotes.');
        }

        return $quotes;
    }
}
