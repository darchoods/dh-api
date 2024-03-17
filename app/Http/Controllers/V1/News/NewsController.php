<?php 

namespace App\Http\Controllers\V1\News;

use App\Http\Controllers\BaseController;
use App\Models\News;

class NewsController extends BaseController
{

    public function getNews()
    {

        $posts = News::getCurrent(5);

        return [
            'posts' => $posts
        ];
    }

    public function getNewsById(News $news)
    {
        return $news->transform();
    }

}
