<?php

namespace Ircbot\Command;

class CtcpRequest extends \Ircbot\Type\MessageCommand
{
    public function  __construct($target = null, $message = null)
    {
        $this->target = $target;
        $this->message = $message;
    }

    public function getEventName()
    {
        return 'onCtcpRequest';
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
            'PRIVMSG %s :' . chr(1) . '%s' . chr(1), $this->target,
            $this->message
        ) . "\n\r";
    }
}
