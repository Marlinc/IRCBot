<?php
namespace Ircbot\Event\Loop;

use Symfony\Component\EventDispatcher\Event;

class Iterated extends Event
{

    private $tick;

    public function __construct($tick)
    {
        $this->tick = $tick;   
    }
    
    public function getTick()
    {
        return $this->tick;
    }

}
