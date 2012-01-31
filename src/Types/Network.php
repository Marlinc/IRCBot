<?php
class IRCBot_Types_Network
{
    public $name;
    public $hostname;
    public $iSupport = array();
    
    public function addISupport(IRCBot_Types_ISupport $iSupport)
    {
        foreach ($iSupport->options as $option => $value) {
            if ($option == 'NETWORK') {
                $this->name = $value;
            }
            $this->iSupport[$option] = $value;    
        }
    }
}
