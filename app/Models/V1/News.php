<?php

namespace App\Models\V1;

use App\Models\BaseModel;
use App\Transformers\V1\NewsTransformer;

class News extends BaseModel
{
    protected $table = 'news';
    protected $transformer = NewsTransformer::class;
    public $fillable = [
        'author_id', 'title', 'slug', 'content', 'view_count', 'publish_at', 'hide'
    ];


    public function author()
    {
        return $this->belongsTo('App\Models\V1\User', 'user_id');
    }
    
    public function scopeGetCurrent($query, $limit = 5)
    {
        return $query->where('publish_at', '<=', date('Y-m-d H:i:s', time()))
            ->whereHide(0)
            ->take($limit)
            ->orderBy('publish_at', 'desc')
            ->get()
        ;
    }

}
