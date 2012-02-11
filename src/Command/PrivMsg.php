<?php

namespace Ircbot\Command;

class PrivMsg extends \Ircbot\Type\MessageCommand
{
    public function  __construct($target = null, $message = null)
    {
        $this->target = $target;
        $this->message = $message;
    }
    public function fromRawData($rawData)
    {
        sscanf(
            $rawData, ':%s PRIVMSG %s :%[ -~]', $this->sender, $this->target,
            $this->message
        );
        $mask = new \Ircbot\Type\Mask();
        $mask->fromMask($this->sender);
        $this->sender = $mask->nickname;
        $this->mask = $mask;
    }
    public function getEventName()
    {
        return 'onPrivMsg';
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
        return sprintf(
            'PRIVMSG %s :%s', $this->target, $this->message
        ) . "\n\r";
    }
}
