<?php

namespace Ircbot\Entity;

class Timer
{
    
    public $callback;
    public $interval;
    public $lastrun;
    public $itertations;
    public $maxIterations = 0;
    private $_name;
    
    public function __construct($name, $callback, $interval = 100)
    {
        if (!is_string($name) || !is_callable($callback) || !is_int($interval)) {
            throw new \InvalidArgumentException;
        }
        $this->_name = $name;
        $this->callback = $callback;
        $this->interval = $interval;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
}

?>
