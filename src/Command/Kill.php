<?php

namespace Ircbot\Command;

class Kill extends \Ircbot\Type\Command
{

    public $source;
    public $user;
    public $comment;

    public function getEventName()
    {
        return 'onKill';
    }

}
