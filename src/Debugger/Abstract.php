<?php

abstract class IRCBot_Debugger_Abstract
{
    const LEVEL_NORMAL = 1;
    const LEVEL_EXTRA = 2;
    
    abstract public function log($category, $type, $message,
        $level = IRCBOT_DEBUG_NORMAL);
    
}
