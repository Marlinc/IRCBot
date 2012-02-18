<?php

namespace Ircbot\Command;

class Pong extends \Ircbot\Type\Command
{
    /**
     * The code send with the pong
     * @var string
     */
    public $code;

    public function getEventName()
    {
        return 'onPong';
    }
    public function  __toString()
    {
        return sprintf('PONG :%s', $this->code) . "\r\n";
    }
}

