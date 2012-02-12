<?php

namespace Ircbot\Numeric;

class ISupport extends Numeric
{
    public $options = array();
    
    public function getEventName()
    {
        $events = parent::getEventName();
        $events[] =  'onISupport';
        return $events;
    }
}
