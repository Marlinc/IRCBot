<?php
class IRCBot_Commands_PrivMsg extends IRCBot_Types_MessageCommand
{
    public function  __construct($target = null, $message = null) {
        $this->target = $target;
        $this->message = $message;
    }
    public function fromRawData($rawData)
    {
        sscanf($rawData, ':%s PRIVMSG %s :%[ -~]', $this->sender, $this->target,
            $this->message);
        $mask = new IRCBot_Types_Mask();
        $mask->fromMask($this->sender);
        $this->sender = $mask->nickname;
        $this->mask = $mask;
    }
    public function getEventName()
    {
        return 'onPrivMsg';
    }
    public function  __toString() {
        return sprintf('PRIVMSG %s :%s', $this->target, $this->message) . "\n\r";
    }
}
?>
