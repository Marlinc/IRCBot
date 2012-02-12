<?php

namespace Ircbot\Numeric;

class NameReply extends Numeric
{
    public $names;
    public $channel;
    
    public function getEventName()
    {
        $events = parent::getEventName();
        $events[] =  'onNameReply';
        return $events;
    }
}
