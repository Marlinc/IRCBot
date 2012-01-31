<?php
/**
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Queues
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The queue handler
 */
class IRCBot_Handlers_Queues
{
    private $_queues = array();
    private $_lastId = 0;
    public function addQueue(IRCBot_Types_Queue $queue)
    {
        ++$this->_lastId;
        $this->_queues[$this->_lastId] = $queue;
        return $this->_lastId;
    }
    /**
     * @param int $queueId
     * @return IRCBot_Types_Queue
     */
    public function &getQueueById($queueId)
    {
        return $this->_queues[$queueId];
    }
}
