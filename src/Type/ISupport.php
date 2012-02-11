<?php

namespace Ircbot\Type;

class ISupport extends Numeric
{
    public $options = array();
    
    public function fromRawData($rawData)
    {
        parent::fromRawData($rawData);
        $rawOptions = explode(' ', strstr($this->message, ' :', true));
        foreach ($rawOptions as &$rawOption) {
            $rawOption = explode('=', $rawOption, 2);
            $this->options[$rawOption[0]] = (isset($rawOption[1]))
                ? $rawOption[1] : null;
        }
    }
    public function getEventName()
    {
        $events = parent::getEventName();
        $events[] =  'onISupport';
        return $events;
    }
}
