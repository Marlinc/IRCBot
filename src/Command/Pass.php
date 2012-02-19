<?php

namespace Ircbot\Command;

class Pass extends \Ircbot\Type\Command
{

    public $pass;

    public function __construct($pass)
    {
        $this->pass = $pass;
    }

    public function getEventName()
    {
        return 'onPass';
    }
    
    public function  __toString()
    {
        return sprintf('PASS %s', $this->pass) . "\r\n";
    }
    
}

