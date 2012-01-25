<?php
/**
 * File with shortened functions
 * 
 * This file contains functions that are use most so they can be called in a much
 * easier fashion
 * 
 * PHP version 5
 * 
 * @category Net
 * @package  IRCBot
 * @author   Marlin Cremers <marlinc@mms-projects.net>
 * @license  http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link     https://github.com/Marlinc/IRCBot
 */

/**
 * Returns a bot class by the given id
 * 
 * @param int $botId The bot id
 * 
 * @return IRCBot_Types_Bot
 * 
 * @see IRCBot_Handlers_Bots::getBotById()
 */
function getBotById($botId = 0)
{
    if (!$botId) {
        $botId = botId();
    }
    return IRCBot_Application::getInstance()->getBotHandler()
        ->getBotById($botId);
}
/**
 * Returns the current channel name
 * 
 * @return string Returns the channel name 
 */
function chan()
{
    return IRCBot_Application::getInstance()->getIdentifierHandler()->chan;
}
/**
 * Returns the nickname of the user who triggered the current event
 * 
 * @return string The nickname of a user
 */
function nick()
{
    return IRCBot_Application::getInstance()->getIdentifierHandler()->nick;
}
/**
 * Returns the bot id that handles the current event
 * 
 * @return int The current bot id
 */
function botId()
{
    return IRCBot_Application::getInstance()->getIdentifierHandler()->botId;
}
/**
 * Send a PRIVMSG from the selected bot
 * 
 * @param string $target  The target of the message
 * @param string $message The actual message to send
 * @param int    $botId   The bot id of the bot that needs to send the message
 *  
 * @return mixed Returns the bot class if succeed else returns false 
 */
function privMsg($target, $message, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->privMsg($target, $message);
    } else {
        return false;
    }
}
/**
 * Send a PRIVMSG from the selected bot
 * 
 * @param string $target  The target of the message
 * @param string $message The actual message to send
 * @param int    $botId   The bot id of the bot that needs to send the message
 *  
 * @return mixed Returns the bot class if succeed else returns false 
 */
function msg($target, $message, $botId = 0)
{
    privMsg($target, $message, $botId);
}
/**
 * Send a NOTICE from the selected bot
 * 
 * @param string $target  The target of the message
 * @param string $message The actual message to send
 * @param int    $botId   The bot id of the bot that needs to send the message
 *  
 * @return mixed Returns the bot class if succeed else returns false 
 */
function notice($target, $message, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->notice($target, $message);
    } else {
        return false;
    }
}
/**
 * Lets the bot join the specified channel
 * 
 * @param string $channel The channel to join
 * @param int    $botId   The bot id of the bot that needs to join the channel
 * 
 * @return Returns the bot class if succeed else false
 */
function joinChan($channel, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->join($channel);
    } else {
        return false;
    }
}
/**
 * Lets the bot part the specified channel
 * 
 * @param string $channel The channel to join
 * @param string $message The message used when parting the channel
 * @param int    $botId   The bot id of the bot that needs to part the channel
 * 
 * @return Returns the bot class if succeed else false
 */
function partChan($channel, $message = null, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->part($channel, $message);
    } else {
        return false;
    }
}
/**
 * Registers a callback with a event
 * 
 * @param string   $eventName The event to register to
 * @param callback $callback  The callback that needs to be called if the event
 *                            raises
 * 
 * @return void
 */
function addEventCallback($eventName, $callback)
{
    IRCBot_Application::getInstance()->getEventHandler()
        ->addEventCallback($eventName, $callback);
}
/**
 * Tokenize a string to use with token
 * 
 * @param string $string The string to tokenize
 * @param int    $chr    The ASCII character code to use when tokenizing    
 * 
 * @return void
 */
function tokenize($string, $chr = 32)
{
    IRCBot_Utilities_String::tokenize($string, $chr);
}
/**
 * Get a token from a tokenized string
 * 
 * @param string $token The token to return
 * 
 * @return string 
 */
function token($token)
{
    return IRCBot_Utilities_String::token($token);
}
?>
