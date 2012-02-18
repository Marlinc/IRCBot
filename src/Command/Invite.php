<?php

namespace Ircbot\Command;

class Invite extends \Ircbot\Type\Command
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
        return sprintf('INVITE %s :%s', $this->target, $this->channel) . "\r\n";
    }
    
}

