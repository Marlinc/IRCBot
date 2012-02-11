<?php

namespace Ircbot\Command;

class Mode extends \Ircbot\Type\Command
{
    public $mask;
    public $target;
    public $modes; 
    
    public function fromRawData($rawData)
    {
        preg_match('/\:(.+) MODE (.+) (\:)?(.+)/', $rawData, $matches);
        list(, $this->mask, $this->target,, $this->modes) = $matches;
        $this->mask = new \Ircbot\Type\Mask($this->mask);
    }
    public function getEventName()
    {
        return 'onMode';
    }
    public function  __toString()
    {
        return sprintf('MODE %s %s', $this->target, $this->modes) . "\n\r";
    }
}

