<?php
class IRCBot_Commands_Mode extends IRCBot_Types_Command
{
    public $mask;
    public $target;
    public $modes; 
    
    public function fromRawData($rawData)
    {
        preg_match('/\:(.+) MODE (.+) (\:)?(.+)/', $rawData, $matches);
        list(, $this->mask, $this->target,, $this->modes) = $matches;
        $this->mask = new IRCBot_Types_Mask($this->mask);
        var_dump($this);
    }
    public function getEventName()
    {
        return 'onMode';
    }
    public function  __toString()
    {
        return sprintf('MODE %s %s', $this->target, $this->modes) . "\n\r";
    }
}
?>
