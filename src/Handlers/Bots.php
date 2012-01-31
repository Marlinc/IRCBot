<?php
/**
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Bots
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The bot handler class
 */
class IRCBot_Handlers_Bots
{
    private $_bots = array();
    private $_lastId = 0;
    public function __construct()
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->addEventCallback('loopIterate', array($this, 'handleBots'));
    }
    public function addBot(IRCBot_Types_Bot &$bot)
    {
        ++$this->_lastId;
        $bot->botId = $this->_lastId;
        $this->_bots[$this->_lastId] = $bot;
        return $this->_lastId;
    }
    public function handleBots()
    {
        foreach (array_keys($this->_bots) as $botId) {
            $bot = $this->getBotById($botId);
            if ($bot->isSocketReady) {
                for ($i = 0; $i < 4; ++$i) {
                    if (!$bot->handleQueueOut()) {
                        break;
                    }
                }
                for ($i = 0; $i < 4; ++$i) {
                    if (!$bot->handleSocket()) {
                        break;
                    }
                }
                $bot->handleQueueIn();
                $bot->handleQueueParsed();
            }
        }
    }
    /**
     *
     * @param int $botId 
     * @return IRCBot_Types_Bot
     */
    public function getBotById($botId)
    {
        return $this->_bots[$botId];
    }
    public function allBotsExecute($method)
    {
        $arguments = func_get_args();
        unset($arguments[0]);
        foreach ($this->_bots as $bot) {
            call_user_func_array(array($bot, $method), $arguments);
        }
    }
}
