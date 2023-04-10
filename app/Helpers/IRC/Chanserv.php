<?php

namespace App\Helpers\IRC;

class Chanserv extends Atheme
{

    public function getList($nickname = 'x')
    {
        $this->addParams('chanserv list');

        $return = $this->doCmd($nickname, $this->getToken(), 'atheme.command');
        return $this->checkResponse($return);
    }

}
