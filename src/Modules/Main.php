<?php
class IRCBot_Modules_Main extends IRCBot_Modules_Abstract
{
    public $events = array(
        'onPing'     => 'onPing',
        'on001'      => 'onConnect',
        'on375'      => 'onMOTDStart',
        'on372'      => 'onMOTD',
        'on332'      => 'onTopic',
        'on333'      => 'onTopicWhoTime',
        'on353'      => 'onNameReply',
        'on366'      => 'onEndNames',
        'on004'      => 'onMyInfo',
        'onJoin'     => 'onJoin',
        'onPart'     => 'onPart',
        'onTopic'    => 'onTopic',
        'onError'    => 'onError',
        'onNameReply'=> 'onNameReply',
        'onISupport' => 'onISupport',
        'SIGINT'     => 'onSIGINT',
    );
    
    private $_tmp = array();
    
    public function onPing(IRCBot_Commands_Ping $ping)
    {
        $bot = IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($ping->botId);
        $pong = new IRCBot_Commands_Pong();
        $pong->code = $ping->code;
        $bot->sendRawData($pong);
    }
    public function onConnect(IRCBot_Types_Numeric $numeric)
    {
        $bot = IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($numeric->botId);
        $bot->isConnected = true;
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('onConnect', $numeric->botId);
    }
    public function onMOTDStart(IRCBot_Types_Numeric $numeric)
    {
        IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($numeric->botId)->serverMOTD = array();
    }
    public function onMOTD(IRCBot_Types_Numeric $numeric)
    {
        IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($numeric->botId)->serverMOTD[] = $numeric->message;
    }
    public function onJoin(IRCBot_Commands_Join $join)
    {
        $bot = IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($join->botId);
        $channelHandler = IRCBot_Application::getInstance()
            ->getChannelHandler();
        $channel = $channelHandler->getChan($join->channel, $join->botId);
        if (!$channel) {
            $channel = $channelHandler->addChan($join->channel, $join->botId);
        }
        if (strtolower($join->mask->nickname) == strtolower($bot->nickname)) {
            $channel->hasBot = true;
        }
    }
    public function onPart(IRCBot_Commands_Part $part)
    {
        $bot = IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($part->botId);
        $channelHandler = IRCBot_Application::getInstance()
            ->getChannelHandler();
        $channel = $channelHandler->getChan($part->channel, $part->botId);
        if (!$channel) {
            $channel = $channelHandler->addChan($part->channel, $part->botId);
        }
        if (strtolower($part->mask->nickname) == strtolower($bot->nickname)) {
            $channelHandler->delChan($part->channel, $part->botId);
        }
    }
    public function onTopic($data)
    {
        if ($data instanceof IRCBot_Types_Numeric) {
            /* @var $data IRCBot_Types_Numeric */
            $topic = new IRCBot_Types_Topic();
            $topic->message = substr(IRCBot_Utilities_String::token('1-'), 1);
            $hash = md5(
                'topic_' . $data->botId . IRCBot_Utilities_String::token('0')
            );
            $this->_tmp[$hash] = $topic;
        } elseif ($data instanceof IRCBot_Commands_Topic) {
            $topic = new IRCBot_Types_Topic();
            $topic->who = $data->who;
            $topic->message = $data->message;
            $topic->timestamp = time();
            $channel = IRCBot_Application::getInstance()->getChannelHandler()
                ->getChan($data->channel, $data->botId);
            $channel->topic = $topic;
            print_r($channel);
        }
    }
    public function onTopicWhoTime(IRCBot_Types_Numeric $numeric)
    {
        $hash = md5(
            'topic_' . $numeric->botId . IRCBot_Utilities_String::token('0')
        );
        /* @var $topic IRCBot_Types_Topic */
        $topic = $this->_tmp[$hash];
        $topic->who = IRCBot_Utilities_String::token('1');
        $topic->timestamp = IRCBot_Utilities_String::token('2');
        unset($this->_tmp[$hash]);
        $channel = IRCBot_Application::getInstance()->getChannelHandler()
            ->getChan(IRCBot_Utilities_String::token('0'), $numeric->botId);
        $channel->topic = $topic;
    }
    public function onError(IRCBot_Commands_Error $error)
    {
        $bot = IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($error->botId);
        $bot->disconnected();
    }
    public function onSIGINT()
    {
        IRCBot_Application::getInstance()->getBotHandler()
            ->allBotsExecute(
                'sendRawData',
                new IRCBot_Commands_Quit('Received SIGINT signal')
            );
        IRCBot_Application::getInstance()->getBotHandler()
            ->allBotsExecute('handleQueueOut', true);
        exit();
    }
    public function onNameReply(IRCBot_Types_NameReply $nameReply)
    {
        $hash = md5('names_' . $nameReply->botId . $nameReply->channel);
        $this->_tmp[$hash][] = $nameReply;
    }
    public function onEndNames(IRCBot_Types_Numeric $numeric)
    {
        $hash = md5(
            'names_' . $numeric->botId . IRCBot_Utilities_String::token('0')
        );
        $names = array();
        foreach ($this->_tmp[$hash] as $nameReply) {
            $names = array_merge($names, $nameReply->names);
        }
        $channel = IRCBot_Application::getInstance()->getChannelHandler()
            ->getChan(IRCBot_Utilities_String::token('0'), $numeric->botId);
        $channel->nicklist = $names;
        unset($this->_tmp[$hash]);
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('channelReady', $numeric);
    }
    public function onISupport(IRCBot_Types_ISupport $numeric)
    {
        $bot = IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($numeric->botId);
        $bot->currentNetwork->addISupport($numeric);
    }
    public function onMyInfo(IRCBot_Types_Numeric $numeric)
    {
        $bot = IRCBot_Application::getInstance()->getBotHandler()
            ->getBotById($numeric->botId);
        $bot->currentNetwork->hostname = IRCBot_Utilities_String::token('0');
    }
}
