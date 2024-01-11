<?php

namespace App\Transformers\V1;

use App\Helpers\V1\DateHelper;
use App\Models\V1\Quote;
use League\Fractal\TransformerAbstract;

class QuoteTransformer extends TransformerAbstract
{
    public function transform(Quote $model)
    {
        return [
            'quote_id'   => (int) $model->quote_id,
            'content'    => (string) $model->content,
            'view_count' => (int) $model->view_count,
            'created'    => DateHelper::date_array($model->created_at),
            'updated'    => DateHelper::date_array($model->updated_at),

            'channel'    => $model->channel->transform(),
            'author'     => $model->author_id,
        ];
    }
}
