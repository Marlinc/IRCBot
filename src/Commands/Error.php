<?php
class IRCBot_Commands_Error extends IRCBot_Types_Command
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
