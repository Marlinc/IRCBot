<?php
class IRCBot_Commands_Nick extends IRCBot_Types_Command
{
    /**
     * The old nickname
     * @var string
     */
    public $oldNick;
    /**
     * The new nickname
     * @var string
     */
    public $newNick;
    public function  __construct($newNick = null) {
        $this->newNick = $newNick;
    }
    public function fromRawData($rawData)
    {
        sscanf($rawData, ':%s NICK :%s', $this->oldNick, $this->newNick);
    }
    public function  __toString() {
        return sprintf('NICK %s', $this->newNick) . "\n\r";
    }
}
?>
