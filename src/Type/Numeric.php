<?php

namespace Ircbot\Type;

class Numeric extends Response
{
    public $botId;
    public $numeric;
    public $server;
    public $target;
    public $message;
    public function fromRawData($rawData)
    {
        sscanf(
            $rawData, ':%s %s %s %[ -~]', $this->server, $this->numeric,
            $this->target, $this->message
        );
    }
    public function getEventName()
    {
        $events = array();
        $events[] = 'onNumeric';
        $events[] = 'on' . $this->numeric;
        return $events;
    }
}
