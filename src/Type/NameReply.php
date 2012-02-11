<?php

namespace Ircbot\Type;

class NameReply extends Numeric
{
    public $names;
    public $channel;
    
    public function fromRawData($rawData)
    {
        parent::fromRawData($rawData);
        $parser = new \Ircbot\Parser\NameReply;
        $this->names = $parser->parseNames($rawData);
        list(,,,, $this->channel) = explode(' ', $rawData);
        
    }
    public function getEventName()
    {
        $events = parent::getEventName();
        $events[] =  'onNameReply';
        return $events;
    }
}
