<?php

namespace Ircbot\Command;

class Error extends \Ircbot\Type\Command
{
    public $message;
    
    public function getEventName()
    {
        return 'onError';
    }
}
