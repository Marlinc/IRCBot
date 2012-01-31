<?php
class IRCBot_Parsers_Commands
{
    public function parseCommand($rawData)
    {
        $rawData = rtrim($rawData);
        echo '[RECV]:: ' . $rawData . PHP_EOL;
        $tmp = explode(' ', $rawData);
        $cmd = null;
        if ((int) $tmp[1]) {
            if ($tmp[1] == RPL_NAMREPLY) {
                $cmd = new IRCBot_Types_NameReply();
            } elseif ($tmp[1] == RPL_ISUPPORT) {
                $cmd = new IRCBot_Types_ISupport();
            } else {
                $cmd = new IRCBot_Types_Numeric();
            }
        } elseif ($tmp[1] == 'NOTICE') {
            $cmd = new IRCBot_Commands_Notice();
        } elseif ($tmp[0] == 'PING') {
            $cmd = new IRCBot_Commands_Ping();
        } elseif ($tmp[0] == 'PONG') {
            $cmd = new IRCBot_Commands_Pong();
        } elseif ($tmp[1] == 'QUIT') {
            $cmd = new IRCBot_Commands_Quit();
        } elseif ($tmp[1] == 'PRIVMSG') {
            $cmd = new IRCBot_Commands_PrivMsg();
        } elseif ($tmp[1] == 'JOIN') {
            $cmd = new IRCBot_Commands_Join();
        } elseif ($tmp[1] == 'PART') {
            $cmd = new IRCBot_Commands_Part();
        } elseif ($tmp[1] == 'TOPIC') {
            $cmd = new IRCBot_Commands_Topic();
        } elseif ($tmp[0] == 'ERROR') {
            $cmd = new IRCBot_Commands_Error();
        } elseif ($tmp[1] == 'INVITE') {
            $cmd = new IRCBot_Commands_Invite();
        } elseif ($tmp[1] == 'MODE') {
            $cmd = new IRCBot_Commands_Mode();
        }
        if ($cmd) {
            $cmd->fromRawData($rawData);
        } else {
            //print_r($rawData);
        }
        return $cmd;
    }
}
