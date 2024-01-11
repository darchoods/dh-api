<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use App\Models\V1\Channel;
use App\Transformers\V1\QuoteTransformer;

class Quote extends BaseModel
{
    protected $table = 'quote_content';
    protected $transformer = QuoteTransformer::class;
    protected $fillable = ['channel_id', 'quote_id', 'author_id', 'content', 'view_count'];

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function author() {
        return $this->belongsTo(User::class);
    }
}
