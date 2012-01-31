<?php
class IRCBot_Commands_Ping extends IRCBot_Types_Command
{
    /**
     * The code send with the ping to send back
     * @var string
     */
    public $code;
    public function fromRawData($rawData)
    {
        sscanf($rawData, 'PING :%[ -~]', $this->code);
    }
    public function getEventName()
    {
        return 'onPing';
    }
    public function  __toString()
    {
        return sprintf('PING :%s', $this->code);
    }
}

