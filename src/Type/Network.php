<?php

namespace Ircbot\Type;

class Network
{
    public $name;
    public $hostname;
    public $iSupport = array();
    
    public function addISupport(ISupport $iSupport)
    {
        foreach ($iSupport->options as $option => $value) {
            if ($option == 'NETWORK') {
                $this->name = $value;
            }
            $this->iSupport[$option] = $value;    
        }
    }
}
