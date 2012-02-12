<?php

namespace Ircbot\Command;

class Nick extends \Ircbot\Type\Command
{
    /**
     * The old nickname
     * @var string
     */
    public $oldNick;
    /**
     * The new nickname
     * @var string
     */
    public $newNick;
    public function  __construct($newNick = null)
    {
        $this->newNick = $newNick;
    }

    public function  __toString()
    {
        return sprintf('NICK %s', $this->newNick) . "\n\r";
    }
}

