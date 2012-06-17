<?php

namespace Ircbot\Handler;

class Timer
{
    
    
    static protected $_timers = array();
    
    static public function addTimer(
        \Ircbot\Entity\Timer &$timer
    ) {
        self::$_timers[$timer->getName()] = $timer;
    }
    
    static public function delTimer($timerName) {
        unset(self::$_timers[$timerName]);
    }
    
    static public function executeTimers($maxTime = 1000)
    {
        $debug = \Ircbot\Application::getInstance()->getDebugger();
        $start = microtime(true);
        foreach (self::$_timers as $name => &$timer) {
            if (microtime(true) * 1000 - $start * 1000 > $maxTime) {
                $debug->log(
                    'timers', 'time-execeed',
                    'Allowed running time exeeded', $debug::LEVEL_DEBUG
                );
                break;
            }
            if (microtime(true) * 1000 - $timer->lastrun * 1000 > $timer->interval) {
                $debug->log(
                    'timers', 'execution',
                    'Timer \'' . $timer->getName() . '\' started', $debug::LEVEL_DEBUG
                );
                call_user_func($timer->callback, $timer);
                ++$timer->iterations;
                $timer->lastrun = microtime(true);
                if ($timer->maxIterations) {
                    if ($timer->iterations == $timer->maxIterations) {
                        $debug->log(
                            'timers', 'done',
                            'Timer \'' . $timer->getName()
                                . '\' done. Removing..', $debug::LEVEL_DEBUG
                        );
                        self::delTimer($timer->getName());
                    }
                }
            }
        }
    }
    
}
?>
