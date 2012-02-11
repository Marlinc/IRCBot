<?php

namespace Ircbot\Parser;

class Commands
{
    public function parseCommand($rawData)
    {
        $rawData = rtrim($rawData);
        echo '[RECV]:: ' . $rawData . PHP_EOL;
        $tmp = explode(' ', $rawData);
        $cmd = null;
        if ((int) $tmp[1]) {
            if ($tmp[1] == RPL_NAMREPLY) {
                $cmd = new \Ircbot\Type\NameReply();
            } elseif ($tmp[1] == RPL_ISUPPORT) {
                $cmd = new \Ircbot\Type\ISupport();
            } else {
                $cmd = new \Ircbot\Type\Numeric();
            }
        } elseif ($tmp[1] == 'NOTICE') {
            $cmd = new \Ircbot\Command\Notice();
        } elseif ($tmp[0] == 'PING') {
            $cmd = new \Ircbot\Command\Ping();
        } elseif ($tmp[0] == 'PONG') {
            $cmd = new \Ircbot\Command\Pong();
        } elseif ($tmp[1] == 'QUIT') {
            $cmd = new \Ircbot\Command\Quit();
        } elseif ($tmp[1] == 'PRIVMSG') {
            $cmd = new \Ircbot\Command\PrivMsg();
        } elseif ($tmp[1] == 'JOIN') {
            $cmd = new \Ircbot\Command\Join();
        } elseif ($tmp[1] == 'PART') {
            $cmd = new \Ircbot\Command\Part();
        } elseif ($tmp[1] == 'TOPIC') {
            $cmd = new \Ircbot\Command\Topic();
        } elseif ($tmp[0] == 'ERROR') {
            $cmd = new \Ircbot\Command\Error();
        } elseif ($tmp[1] == 'INVITE') {
            $cmd = new \Ircbot\Command\Invite();
        } elseif ($tmp[1] == 'MODE') {
            $cmd = new \Ircbot\Command\Mode();
        }
        if ($cmd) {
            $cmd->fromRawData($rawData);
        } else {
            //print_r($rawData);
        }
        return $cmd;
    }
}
