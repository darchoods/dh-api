<?php 

namespace App\Http\Controllers\V1\News;

use App\Http\Controllers\BaseApiController;
use App\Models\V1\News;

class NewsController extends BaseApiController
{

    public function getAll()
    {

        $posts = News::with('author')->getCurrent(5);

        return $this->sendOK([
            'posts' => $posts->map->transform()
        ]);
    }

    public function getById(News $news)
    {
        $news->load('author');
        
        return $this->sendOK([
            'post' => $news->transform()
        ]);
    }

}
