<?php
class IRCBot_Commands_Invite extends IRCBot_Types_Command
{
    /**
     * @var IRCBot_Types_Mask
     */
    public $mask;
    public $target;
    public $channel;
    public function  __construct($channel = null)
    {
        $this->channel = $channel;
    }
    public function fromRawData($rawData)
    {
        preg_match('/^:(.+) INVITE (.+) (:)?(.+)$/', $rawData, $matches);
        list(, $this->mask, $this->target, , $this->channel) = $matches;
        $mask = new IRCBot_Types_Mask();
        $mask->fromMask($this->mask);
        $this->mask = $mask;
    }
    public function getEventName()
    {
        return 'onInvite';
    }
    public function getIdentifiers()
    {
        $identifiers = array();
        $identifiers['chan'] = $this->getChan();
        $identifiers['nick'] = $this->mask->nickname;
        return $identifiers;
    }
    public function  __toString()
    {
        return sprintf('INVITE %s :%s', $this->target, $this->channel) . "\n\r";
    }
}

