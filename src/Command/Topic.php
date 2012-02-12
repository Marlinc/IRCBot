<?php

namespace Ircbot\Command;

class Topic extends \Ircbot\Type\Command
{
    public $channel;
    public $who;
    public $mask;
    public $message;
    public function  __construct($channel = null, $message = null) 
    {
        $this->channel = $channel;
        $this->message = $message;
    }

    public function getEventName()
    {
        return 'onTopic';
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
        return sprintf('TOPIC %s :%s', $this->channel, $this->message) . "\n\r";
    }
}
