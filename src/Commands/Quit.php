<?php
class IRCBot_Commands_Quit extends IRCBot_Types_Command
{
    public $mask;
    public $message;
    public function  __construct($message = null) {
        $this->message = $message;
    }
    public function fromRawData($rawData)
    {
        sscanf($rawData, ':%s QUIT :%[ -~]', $this->mask, $this->message);
    }
    public function  __toString() {
        return sprintf('QUIT :%s', $this->message) . "\n\r";
    }
}
?>
