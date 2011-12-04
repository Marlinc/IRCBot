<?php
class IRCBot_Commands_Topic extends IRCBot_Types_Command {
    public $channel;
    public $who;
    public $mask;
    public $message;
    public function  __construct($channel = null, $message = null) {
        $this->channel = $channel;
        $this->message = $message;
    }
    public function fromRawData($rawData) {
        sscanf($rawData, ':%s TOPIC %s :%[ -~]', $this->who, $this->channel,
            $this->message);
        $mask = new IRCBot_Types_Mask();
        $mask->fromMask($this->who);
        $this->who = $mask->nickname;
        $this->mask = $mask;
    }
    public function  __toString() {
        return sprintf('TOPIC %s :%s', $this->channel, $this->message) . "\n\r";
    }
}
?>
