<?php
define('RPL_WELCOME', '001');
define('RPL_YOURHOST', '002');
define('RPL_CREATED', '003');
define('RPL_MYINFO', '004');
define('RPL_BOUNCE', '005');
define('RPL_ISUPPORT', '005');
define('RPL_MAP', '006');
define('RPL_MAPEND', '007');
define('RPL_LUSERCLIENT', '251');
define('RPL_LUSEROP', '252');
define('RPL_LUSERCHANNELS', '254');
define('RPL_LUSERME', '255');
define('RPL_LOCALUSERS', '265');
define('RPL_GLOBALUSERS', '266');
define('RPL_TOPIC', '332');
define('RPL_TOPICWHOTIME', '333');
define('RPL_NAMREPLY', '353');
define('RPL_ENDOFNAMES', '366');
define('RPL_MOTDSTART', '375');
define('RPL_MOTD', '372');
define('RPL_ENDOFMOTD', '376');
define('ERR_BADCHANNELKEY', '475');

class IRCBot_Handlers_Responses
{
    public function handleResponse(IRCBot_Types_Response $response)
    {
        if ($response instanceof IRCBot_Types_Command) {
            $this->handleCommand($response);
        }
        elseif ($response instanceof IRCBot_Types_Numeric) {
            $this->handleNumeric($response);
        }
    }
    public function handleCommand(IRCBot_Types_Command $command)
    {
        $eventHandler = IRCBot_Application::getInstance()->getEventHandler();
        $identifiers = IRCBot_Application::getInstance()->getIdentifierHandler();
        $identifiers->botId = $command->botId;
        if ($command instanceof IRCBot_Commands_Notice) {
            $identifiers->set('chan', $command->getChan())
                ->set('nick', $command->mask->nickname);
            IRCBot_Utilities_String::tokenize($command->message);
        }
        elseif ($command instanceof IRCBot_Commands_Ping) {
        }
        elseif ($command instanceof IRCBot_Commands_Pong) {
        }
        elseif ($command instanceof IRCBot_Commands_Quit) {
            IRCBot_Utilities_String::tokenize($command->message);
        }
        elseif ($command instanceof IRCBot_Commands_PrivMsg) {
            $identifiers->set('chan', $command->getChan())
                ->set('nick', $command->mask->nickname);
            IRCBot_Utilities_String::tokenize($command->message);
        }
        elseif ($command instanceof IRCBot_Commands_Join) {
            $identifiers->set('chan', $command->channel)
                ->set('nick', $command->mask->nickname);
        }
        elseif ($command instanceof IRCBot_Commands_Part) {
            $identifiers->set('chan', $command->channel)
                ->set('nick', $command->mask->nickname);
            IRCBot_Utilities_String::tokenize($command->message);
        }
        elseif ($command instanceof IRCBot_Commands_Topic) {
            $identifiers->set('chan', $command->channel)
                ->set('nick', $command->mask->nickname);
            IRCBot_Utilities_String::tokenize($command->message);
        }
        elseif ($command instanceof IRCBot_Commands_Error) {
            IRCBot_Utilities_String::tokenize($command->message);
        }
        elseif ($command instanceof IRCBot_Commands_Invite) {
            $identifiers->set('chan', $command->channel)
                ->set('nick', $command->mask->nickname);
        }
        $eventHandler->raiseEvent($command->getEventName(), $command);
    }
    public function handleNumeric(IRCBot_Types_Numeric $numeric)
    {
        $eventHandler = IRCBot_Application::getInstance()->getEventHandler();
        IRCBot_Utilities_String::tokenize($numeric->message);
        $eventHandler->raiseEvent('onNumeric', $numeric);
        $eventHandler->raiseEvent('on' . $numeric->numeric, $numeric);
        if ($numeric instanceof IRCBot_Types_NameReply) {
            $identifiers = IRCBot_Application::getInstance()->getIdentifierHandler();
            $identifiers->botId = $numeric->botId;
            $eventHandler->raiseEvent('onNameReply', $numeric);
        } elseif ($numeric instanceof IRCBot_Types_ISupport) {
            $identifiers = IRCBot_Application::getInstance()->getIdentifierHandler();
            $identifiers->botId = $numeric->botId;
            $eventHandler->raiseEvent('onISupport', $numeric);
        }
    }
}
?>
