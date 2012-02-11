<?php
/*
 * @category IRCBot
 * @package IRCBot_Types
 * @subpackage Bot
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

namespace Ircbot\Type;

/**
 * The bot class
 */
class Bot
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
        $app = \Ircbot\Application::getInstance();
        $queueHandler = $app->getQueueHandler();
        $socket = new \Ircbot\Type\Socket();
        $socket->create(AF_INET, SOCK_STREAM, SOL_TCP);
        $this->_socketId = $app->getSocketHandler()->addSocket($socket);
        $this->_queueInId = $queueHandler->addQueue(
            new \Ircbot\Type\Queue()
        );
        $this->_queueOutId = $queueHandler->addQueue(
            new \Ircbot\Type\Queue()
        );
        $this->_queueParsedId = $queueHandler->addQueue(
            new \Ircbot\Type\Queue()
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
        $socket = \Ircbot\Application::getInstance()->getSocketHandler()
            ->getSocketById($this->_socketId);
        $socket->connect($this->serverAddress, $this->serverPort);
        $socket->setNonBlock();
        $queue = \Ircbot\Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueOutId);
        $queue->addEntry(new \Ircbot\Command\Nick($this->nickname));
        $queue->addEntry(
            new \Ircbot\Command\User($this->ident, $this->realName)
        );
        $this->isSocketReady = true;
        $this->currentNetwork = new \Ircbot\Type\Network;
        $this->handleQueueOut();
    }
    public function handleSocket()
    {
        $socket = \Ircbot\Application::getInstance()->getSocketHandler()
            ->getSocketById($this->_socketId);
        $queue = \Ircbot\Application::getInstance()->getQueueHandler()
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
        $queue = \Ircbot\Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueOutId);
        while (!$queue->isEmpty()) {
            $data = (string) $queue->shift();
            $socket = \Ircbot\Application::getInstance()->getSocketHandler()
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
        $queue = \Ircbot\Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueInId);
        $queueParsed = \Ircbot\Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueParsedId);
        if (!$queue->isEmpty()) {
            $data = \Ircbot\Application::getInstance()->getCommandParser()
                ->parseCommand($queue->shift());
            if ($data) {
                $queueParsed->addEntry($data);
            }
        }
        return !$queue->isEmpty();
    }
    public function handleQueueParsed()
    {
        $queue = \Ircbot\Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueParsedId);
        if (!$queue->isEmpty()) {
            $data = $queue->shift();
            $data->botId = $this->botId;
            \Ircbot\Application::getInstance()->getResponseHandler()
                ->handleResponse($data);
        }
        return !$queue->isEmpty();
    }
    public function sendRawData($rawData)
    {
        $queue = \Ircbot\Application::getInstance()->getQueueHandler()
            ->getQueueById($this->_queueOutId);
        $queue->addEntry($rawData);
        return $this;
    }
    public function privMsg($target, $message)
    {
        $this->sendRawData(new \Ircbot\Command\PrivMsg($target, $message));
        return $this;
    }
    public function msg($target, $message)
    {
        $this->privMsg($target, $message);
        return $this;
    }
    public function notice($target, $message)
    {
        $this->sendRawData(new \Ircbot\Command\Notice($target, $message));
        return $this;
    }
    public function join($channel)
    {
        $this->sendRawData(new \Ircbot\Command\Join($channel));
        return $this;
    }
    public function part($channel, $message = null)
    {
        $this->sendRawData(new \Ircbot\Command\Part($channel, $message));
        return $this;
    }
    public function disconnected()
    {
        $this->isConnected = false;
        $this->isSocketReady = false;
        $socket = \Ircbot\Application::getInstance()->getSocketHandler()
            ->getSocketById($this->_socketId);
        \Ircbot\Application::getInstance()->getEventHandler()
            ->raiseEvent('onDisconnected', $this->botId);
    }
}
