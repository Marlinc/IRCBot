<?php

namespace Ircbot\Parser;

class Command
{

    public function __invoke($rawdata)
    {
        $maskParser    = new Mask;
        $tmp = explode(' ', $rawdata);
        if ($tmp[0] == 'PING') {
            $cmd = new \Ircbot\Command\Ping;
            sscanf($rawdata, 'PING :%[ -~]', $cmd->code);
        } elseif ($tmp[1] == 'NOTICE') {
            $cmd = new \Ircbot\Command\Notice;
            preg_match('/^:(.+) NOTICE (.+) :(.*)$/', $rawdata, $matches);
            list(, $cmd->sender, $cmd->target, $cmd->message) = $matches;
            if (($cmd->message[0] == chr(1))
              && ($cmd->message[strlen($cmd->message) - 1] == chr(1))) {
                $ctcp = new \Ircbot\Command\CtcpReply;
                $ctcp->sender = $cmd->sender;
                $ctcp->target = $cmd->target;
                $ctcp->message = substr(
                    $cmd->message, 1, strlen($cmd->message) - 2
                );
                $cmd = $ctcp;
            }
            $cmd->mask = $maskParser($cmd->sender);
        } elseif ($tmp[1] == 'MODE') {
            $cmd = new \Ircbot\Command\Mode;
            preg_match('/\:(.+) MODE (.+) (\:)?(.+)/', $rawdata, $matches);
            list(, $cmd->mask, $cmd->target,, $cmd->modes) = $matches;
            $cmd->mask = $maskParser($cmd->mask);
        } elseif ($tmp[1] == 'JOIN') {
            $cmd = new \Ircbot\Command\Join;
            preg_match('/^:(.+) JOIN (:)?(.+)$/', $rawdata, $matches);
            list(, $cmd->mask, , $cmd->channel) = $matches;
            $cmd->mask = $maskParser($cmd->mask);
        } elseif ($tmp[1] == 'PONG') {
            $cmd = new \Ircbot\Command\Pong;
            sscanf($rawdata, 'PING :%[ -~]', $cmd->code);
        } elseif ($tmp[1] == 'QUIT') {
            $cmd = new \Ircbot\Command\Quit;
            sscanf($rawdata, ':%s QUIT :%[ -~]', $cmd->mask, $cmd->message);
        } elseif ($tmp[1] == 'PRIVMSG') {
            $cmd = new \Ircbot\Command\PrivMsg;
            preg_match('/^:(.+) PRIVMSG (.+) :(.*)$/', $rawdata, $matches);
            list(, $cmd->sender, $cmd->target, $cmd->message) = $matches;
            if (($cmd->message[0] == chr(1))
              && ($cmd->message[strlen($cmd->message) - 1] == chr(1))) {
                $ctcp = new \Ircbot\Command\CtcpRequest;
                $ctcp->sender = $cmd->sender;
                $ctcp->target = $cmd->target;
                $ctcp->message = substr(
                    $cmd->message, 1, strlen($cmd->message) - 2
                );
                $cmd = $ctcp;
            }
            $cmd->mask = $maskParser($cmd->sender);
        } elseif ($tmp[1] == 'PART') {
            $cmd = new \Ircbot\Command\Part;
            sscanf(
                $rawdata, ':%s PART %s :%s', $cmd->mask, $cmd->channel,
                $cmd->message
            );
            $cmd->mask = $maskParser($cmd->mask);
        } elseif ($tmp[1] == 'INVITE') {
            $cmd = new \Ircbot\Command\Invite;
            preg_match('/^:(.+) INVITE (.+) (:)?(.+)$/', $rawdata, $matches);
            list(, $cmd->mask, $cmd->target, , $cmd->channel) = $matches;
            $cmd->mask = $maskParser($cmd->mask);
        } elseif ($tmp[1] == 'ERROR') {
            $cmd = new \Ircbot\Command\Error;
            sscanf($rawdata, 'ERROR :%s', $cmd->message);
        } elseif ($tmp[1] == 'TOPIC') {
            $cmd = new \Ircbot\Command\Topic;
            sscanf(
                $rawdata, ':%s TOPIC %s :%[ -~]', $cmd->who, $cmd->channel,
                $cmd->message
            );
            $cmd->mask = $maskParser($cmd->mask);
            $cmd->who = $cmd->mask->nickname;
        }
        return $cmd;
    }

}
