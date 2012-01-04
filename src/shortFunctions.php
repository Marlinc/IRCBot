<?php
/**
 * File with shortened functions
 * 
 * This file contains functions that are use most so they can be called in a much
 * easier fashion
 * 
 * PHP version 5
 * 
 * @author  Marlin Cremers <marlinc@mms-projects.net>
 * @license http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link    https://github.com/Marlinc/IRCBot
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
function botId()
{
    return IRCBot_Application::getInstance()->getIdentifierHandler()->botId;
}
function privMsg($target, $message, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->privMsg($target, $message);
    } else {
        return false;
    }
}
function msg($target, $message, $botId = 0)
{
    privMsg($target, $message, $botId);
}
function notice($target, $message, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->notice($target, $message);
    } else {
        return false;
    }
}
function joinChan($channel, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->join($channel);
    } else {
        return false;
    }
}
function partChan($channel, $message = null, $botId = 0)
{
    $bot = getBotById($botId);
    if ($bot) {
        return $bot->part($channel, $message);
    } else {
        return false;
    }
}
function addEventCallback($eventName, $callback)
{
    IRCBot_Application::getInstance()->getEventHandler()
        ->addEventCallback($eventName, $callback);
}
function tokenize($string, $chr = 32)
{
    IRCBot_Utilities_String::tokenize($string, $chr);
}
function token($token)
{
    return IRCBot_Utilities_String::token($token);
}
?>
