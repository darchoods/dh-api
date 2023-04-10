<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use App\Transformers\V1\ChannelTransformer;
use App\Models\V1\Quote;

class Channel extends BaseModel
{
    protected $table = 'quote_channels';
    protected $transformer = ChannelTransformer::class;
    protected $fillable = ['channel'];

    public function quotes() {
        return $this->hasMany(Quote::class);
    }
}
