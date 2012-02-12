<?php

namespace Ircbot\Parser;

class Irc
{

    public function __invoke($rawdata)
    {
        $rawdata = rtrim($rawdata);
        echo $rawdata . PHP_EOL;
        $maskParser    = new Mask;
        $numericParser = new Numeric;
        $commandParser = new Command;
        $tmp = explode(' ', $rawdata);
        if (is_numeric($tmp[1])) {
            return $numericParser($rawdata);
        } else {
            return $commandParser($rawdata);
        }
    }
    
    
}
