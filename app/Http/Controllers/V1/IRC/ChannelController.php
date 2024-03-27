<?php 

namespace App\Http\Controllers\V1\IRC;

use App\Http\Controllers\BaseApiController;
use App\Models\V1\Channels;

class ChannelController extends BaseApiController
{

    public function getChannels()
    {

        $posts = Channels::with('author')->getCurrent(5);

        return $this->sendOK([
            'posts' => $posts->map->transform()
        ]);
    }


}
