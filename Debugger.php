<?php
define('IRCBOT_DEBUG_NORMAL', 1);
define('IRCBOT_DEBUG_EXTRA', 2);

class IRCBot_Debugger
{
    public function log($category, $type, $message, $level = IRCBOT_DEBUG_NORMAL)
    {
        echo sprintf('[%s][%s]:: %s', $category, $type, $message) . PHP_EOL;
    }
}
?>
