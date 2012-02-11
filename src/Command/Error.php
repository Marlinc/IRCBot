<?php

namespace Ircbot\Command;

class Error extends \Ircbot\Type\Command
{
    public $message;
    
    public function fromRawData($rawData)
    {
        sscanf($rawData, 'ERROR :%s', $this->message);
    }
    
    public function getEventName()
    {
        return 'onError';
    }
}
