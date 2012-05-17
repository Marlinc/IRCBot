<?php

namespace Ircbot\Application\Debug;

abstract class ADebug
{

    const LEVEL_DEBUG = 1;
    const LEVEL_INFO  = 2;
    const LEVEL_WARN  = 4;
    const LEVEL_ERROR = 8;
    const LEVEL_FATAL = 16;
    
    abstract public function log(
        $category, $type, $message, $level = self::LEVEL_INFO
    );
    
}
