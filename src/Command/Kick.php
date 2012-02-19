<?php

namespace Ircbot\Command;

class Kick extends \Ircbot\Type\Command
{
    
    public $source;
    public $channel;
    public $user;
    public $comment;
        
    public function getEventName()
    {
        return 'onKick';
    }

    public function  __toString()
    {
        return sprintf(
            'KICK %s', $this->channel, $this->user, $this->comment
        ) . "\r\n";
    }
    
}

