<?php

namespace Ircbot\Numeric;

class Numeric extends \Ircbot\Type\Response
{
    public $botId;
    public $numeric;
    public $server;
    public $target;
    public $message;
    
    public function getEventName()
    {
        $events = array();
        $events[] = 'onNumeric';
        $events[] = 'on' . $this->numeric;
        return $events;
    }
}
