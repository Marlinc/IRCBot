<?php
function getBotById($botId = 0)
{
    if (!$botId) {
        $botId = botId();
    }
    return IRCBot_Application::getInstance()->getBotHandler()
        ->getBotById($botId);
}
function chan()
{
    return IRCBot_Application::getInstance()->getIdentifierHandler()->chan;
}
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
