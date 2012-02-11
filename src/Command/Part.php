<?php

namespace Ircbot\Command;

class Part extends \Ircbot\Type\Command
{
    /**
     * @var IRCBot_Types_Mask
     */
    public $mask;
    public $channel;
    public $message = '';
    public function  __construct($channel = null, $message = null)
    {
        $this->channel = $channel;
        $this->message = $message;
    }
    public function fromRawData($rawData)
    {
        sscanf(
            $rawData, ':%s PART %s :%s', $this->mask, $this->channel,
            $this->message
        );
        $mask = new Ircbot\Type\Mask();
        $mask->fromMask($this->mask);
        $this->mask = $mask;
    }
    public function getEventName()
    {
        return 'onPart';
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
        return sprintf('PART %s :%s', $this->channel, $this->message) . "\n\r";
    }
}

