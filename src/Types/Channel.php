<?php
define('CHAN_MODE_OWNER', 1);
define('CHAN_MODE_ADMIN', 2);
define('CHAN_MODE_OP', 4);
define('CHAN_MODE_HALFOP', 8);
define('CHAN_MODE_VOICE', 16);

class IRCBot_Types_Channel
{
    /**
     * The name of the specific channel
     * @var string
     */
    public $name;
    /**
     * @var IRCBot_Types_Topic
     */
    public $topic;
    /**
     * If the channel has a bot running on this framework.
     * Mostly yes
     * @var bool
     */
    public $nicklist;
    public $hasBot;
    
    public function isOp($askedNick)
    {
        foreach ($this->nicklist as $nick => $rank) {
            if (strtolower($nick) == strtolower($askedNick)) {
                if ($rank & CHAN_MODE_OP) {
                    return true;
                }
            }
        }
        return false;
    }
}
