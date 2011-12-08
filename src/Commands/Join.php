<?php
class IRCBot_Commands_Join extends IRCBot_Types_Command
{
    /**
     * @var IRCBot_Types_Mask
     */
    public $mask;
    public $channel;
    public function  __construct($channel = null)
    {
        $this->channel = $channel;
    }
    public function fromRawData($rawData)
    {
        preg_match('/^:(.+) JOIN (:)?(.+)$/', $rawData, $matches);
        list(, $this->mask, , $this->channel) = $matches;
        $mask = new IRCBot_Types_Mask();
        $mask->fromMask($this->mask);
        $this->mask = $mask;
    }
    public function getEventName()
    {
        return 'onJoin';
    }
    public function getIdentifiers()
    {
        $identifiers = array();
        $identifiers['chan'] = $this->channel;
        $identifiers['nick'] = $this->mask->nickname;
        return $identifiers;
    }
    public function  __toString()
    {
        return sprintf('JOIN :%s', $this->channel) . "\n\r";
    }
}
?>
