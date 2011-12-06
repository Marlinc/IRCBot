<?php
class IRCBot_Commands_Pong extends IRCBot_Types_Command
{
    /**
     * The code send with the pong
     * @var string
     */
    public $code;
    public function fromRawData($rawData)
    {
        sscanf($rawData, 'PONG :%[ -~]', $this->code);
    }
    public function  __toString() {
        return sprintf('PONG :%s', $this->code) . "\n\r";
    }
}
?>
