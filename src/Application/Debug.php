<?php
/**
 * Debugger file class
 * 
 * PHP version 5
 * 
 * @category Net
 * @package  IRCBot
 * @author   Marlin Cremers <marlinc@mms-projects.net>
 * @license  http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link     https://github.com/Marlinc/IRCBot
 */
 
namespace Ircbot\Application;

define('IRCBOT_DEBUG_NORMAL', 1);
define('IRCBOT_DEBUG_EXTRA', 2);

/**
 * This class is used to function as debugging/logging mechanism
 * 
 * @category Net
 * @package  IRCBot
 * @author   Marlin Cremers <marlinc@mms-projects.net>
 * @license  http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link     https://github.com/Marlinc/IRCBot
 */
class Debug extends Debug\ADebug
{
    /**
     * Log to STDOUT
     * 
     * @param string $category The category the log item belongs to
     * @param string $type     The type of message
     * @param string $message  The actual message
     * @param int    $level    The debugging level needed to log its
     * 
     * @return void
     */
    public function log($category, $type, $message,
        $level = IRCBOT_DEBUG_NORMAL)
    {
        echo sprintf('[%s][%s]:: %s', $category, $type, $message) . PHP_EOL;
    }
}
