<?php

namespace Ircbot\Type;

class Command extends Response
{
    /**
     * The bot id that belongs to the command
     * @var int
     */
    public $botId = 0;
    public function fromRawData($rawData)
    {
    }
    public function getIdentifiers()
    {
        return array();
    }
}
