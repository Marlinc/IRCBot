<?php
namespace Ircbot\Event\Irc;

use Symfony\Component\EventDispatcher\Event;

class Connected extends Event
{

    private $botId;
    private $numeric;

    public function __construct($botId, $numeric)
    {
        $this->botId   = $botId;
        $this->numeric = $numeric;
    }
    
    public function getBotId()
    {
        return $this->botId;
    }

}
