<?php
class IRCBot_Types_Command extends IRCBot_Types_Response
{
    /**
     * The bot id that belongs to the command
     * @var int
     */
    public $botId = 0;
    public function fromRawData($rawData) {}
    public function getIdentifiers()
    {
        return array();
    }
}
?>
