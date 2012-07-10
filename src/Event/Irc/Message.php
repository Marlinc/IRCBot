<?php
namespace Ircbot\Event\Irc;

use Symfony\Component\EventDispatcher\Event;

class Message extends Event
{

    private $sender;
    private $target;
    private $message;

    public function __construct($sender, $target, $message)
    {
        $this->sender  = $sender;
        $this->target  = $target;
        $this->message = $message;
    }
    
    public function getSender()
    {
        return $this->sender;
    }
    
    public function getTarget()
    {
        return $this->target;
    }
    
    public function targetIsChannel()
    {
        return (substr($this->target, 0, 1) == '#');
    }
    
    public function getMessage()
    {
        return $this->message;
    }

}
