<?php
/*
 * @category IRCBot
 * @package IRCBot_Types
 * @subpackage Bot
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The bot class
 */
class IRCBot_Types_Bot
{
    public $nickname = 'PHPIRCBot';
    public $nicknameAlternative = 'PHPIRCBot_';
    public $ident = 'PHPIRCBot';
    public $realName = 'A PHPIRCBot creation';
    public $botId = 0;
    public $serverAddress = null;
    public $serverPort = 6667;
    public $serverMOTD = array();
    public $currentNetwork;
    private $_socketId = 0;
    private $_queueInId = 0;
    private $_queueParsedId = 0;
    private $_queueOutId = 0;
    public $isConnected = false;
    public $isSocketReady = false;
    public function  __construct()
    {
        $app = IRCBot_Application::getInstance();
        $queueHandler = $app->getQueueHandler();
        $socket = new IRCBot_Types_Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $this->_socketId = $app->getSocketHandler()->addSocket($socket);
        $this->_queueInId = $queueHandler->addQueue(
            new IRCBot_Types_Queue()
        );
        $this->_queueOutId = $queueHandler->addQueue(
            new IRCBot_Types_Queue()
        );
        $this->_queueParsedId = $queueHandler->addQueue(
            new IRCBot_Types_Queue()
        );
    }
    public function connect($address = null, $port = null)
    {
        if ($address) {
            $this->serverAddress = $address;
        }
        if ($port) {
            $this->serverPort = $port;
        }
        $socket = IRCBot_Application::getInstance()->getSocketHandler()
            ->getSocketById($this->_socketId);
        $socket->connect($this->serverAddress, $this->serverPort);
        $socket->setNonBlock();
        $queue = IRCBot_Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueOutId);
        $queue->addEntry(new IRCBot_Commands_Nick($this->nickname));
        $queue->addEntry(
            new IRCBot_Commands_User($this->ident, $this->realName)
        );
        $this->isSocketReady = true;
        $this->currentNetwork = new IRCBot_Types_Network;
        $this->handleQueueOut();
    }
    public function handleSocket()
    {
        $socket = IRCBot_Application::getInstance()->getSocketHandler()
            ->getSocketById($this->_socketId);
        $queue = IRCBot_Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueInId);
        $data = @$socket->readLine();
        if ($data === false) {
            $this->disconnected();
            return false;
        }
        if ($data) {
            $queue->addEntry($data);
        }
        return !empty($data);
    }
    public function handleQueueOut($completely = false)
    {
        $queue = IRCBot_Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueOutId);
        while (!$queue->isEmpty()) {
            $data = (string) $queue->shift();
            $socket = IRCBot_Application::getInstance()->getSocketHandler()
                ->getSocketById($this->_socketId);
            echo '[SEND]:: ' . $data;
            while (!$status = @$socket->write($data)) {
                if ($status === false) {
                    $this->disconnected();
                    return false;
                }
                usleep(100);
            }
        }
        return !$queue->isEmpty();
    }
    public function handleQueueIn()
    {
        $queue = IRCBot_Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueInId);
        $queueParsed = IRCBot_Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueParsedId);
        if (!$queue->isEmpty()) {
            $data = IRCBot_Application::getInstance()->getCommandParser()
                ->parseCommand($queue->shift());
            if ($data) {
                $queueParsed->addEntry($data);
            }
        }
        return !$queue->isEmpty();
    }
    public function handleQueueParsed()
    {
        $queue = IRCBot_Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueParsedId);
        if (!$queue->isEmpty()) {
            $data = $queue->shift();
            $data->botId = $this->botId;
            IRCBot_Application::getInstance()->getResponseHandler()
                ->handleResponse($data);
        }
        return !$queue->isEmpty();
    }
    public function sendRawData($rawData)
    {
        $queue = IRCBot_Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueOutId);
        $queue->addEntry($rawData);
        return $this;
    }
    public function privMsg($target, $message)
    {
        $this->sendRawData(new IRCBot_Commands_PrivMsg($target, $message));
        return $this;
    }
    public function msg($target, $message)
    {
        $this->privMsg($target, $message);
        return $this;
    }
    public function notice($target, $message)
    {
        $this->sendRawData(new IRCBot_Commands_Notice($target, $message));
        return $this;
    }
    public function join($channel)
    {
        $this->sendRawData(new IRCBot_Commands_Join($channel));
        return $this;
    }
    public function part($channel, $message = null)
    {
        $this->sendRawData(new IRCBot_Commands_Part($channel, $message));
        return $this;
    }
    public function disconnected()
    {
        $this->isConnected = false;
        $this->isSocketReady = false;
        $socket = IRCBot_Application::getInstance()->getSocketHandler()
            ->getSocketById($this->_socketId);
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('onDisconnected', $this->botId);
    }
}
