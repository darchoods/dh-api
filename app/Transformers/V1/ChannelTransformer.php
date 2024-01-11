<?php

namespace App\Transformers\V1;

use App\Models\V1\Channel;
use League\Fractal\TransformerAbstract;

class ChannelTransformer extends TransformerAbstract
{
    public function transform(Channel $model)
    {
        return [
            'channel_id'  => $model->id,
            'channel'     => $model->channel,
            'quote_count' => $model->quote_count,
        ];
    }
}
