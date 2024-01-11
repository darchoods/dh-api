<?php

namespace App\Helpers\IRC\Server;

class Chanserv extends Atheme
{

    public function getList($nickname = 'x', $authToken)
    {
        $this->addParams('chanserv list *');

        $return = $this->doCmd($nickname, $authToken, 'atheme.command');
        return $this->checkResponse($return);
    }

}
