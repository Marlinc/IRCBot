<?php

namespace Ircbot\Command;

class Notice extends \Ircbot\Type\MessageCommand
{
    public function  __construct($target = null, $message = null)
    {
        $this->target = $target;
        $this->message = $message;
    }

    public function getEventName()
    {
        return 'onNotice';
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
        return sprintf('NOTICE %s :%s', $this->target, $this->message) . "\r\n";
    }
}
