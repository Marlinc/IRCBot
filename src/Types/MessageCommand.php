<?php
class IRCBot_Types_MessageCommand extends IRCBot_Types_Command
{
    /**
     * @var IRCBot_Types_Mask
     */
    public $mask;
    /**
     * The sender where the message came from
     * @var string
     */
    public $sender;
    /**
     * The target of the message
     * @var string
     */
    public $target;
    /**
     * The message send
     * @var string
     */
    public $message;
    /**
     * The regex matches
     * @var array
     */
    public $matches;
    public function onChan()
    {
        return (substr($this->target, 0, 1) == '#');
    }
    public function getChan()
    {
        return (substr($this->target, 0, 1) == '#') ? $this->target : '';
    }
}
