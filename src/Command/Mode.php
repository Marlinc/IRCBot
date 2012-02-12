<?php

namespace Ircbot\Command;

class Mode extends \Ircbot\Type\Command
{
    public $mask;
    public $target;
    public $modes; 
    
    public function getEventName()
    {
        return 'onMode';
    }
    public function  __toString()
    {
        return sprintf('MODE %s %s', $this->target, $this->modes) . "\n\r";
    }
}

