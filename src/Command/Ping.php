<?php

namespace Ircbot\Command;

class Ping extends \Ircbot\Type\Command
{
    /**
     * The code send with the ping to send back
     * @var string
     */
    public $code;

    public function getEventName()
    {
        return 'onPing';
    }
    public function  __toString()
    {
        return sprintf('PING :%s', $this->code) . "\r\n";
    }
}

