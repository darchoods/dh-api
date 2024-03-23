<?php

namespace App\Transformers\V1;

use App\Helpers\V1\DateHelper;
use App\Models\V1\News;
use League\Fractal\TransformerAbstract;

class NewsTransformer extends TransformerAbstract
{
    public function transform(News $model)
    {
        return [
            'id'         => (int) $model->id,
            'title'      => (string) $model->title,
            'content'    => (string) $model->content,
            'slug'       => (string) $model->slug,

            'publish_at' => date_array($model->publish_at),
            'created_at' => date_array($model->created_at),
            'updated_at' => date_array($model->updated_at),
            'author'     => $model->author?->transform(),
        ];
    }
}
