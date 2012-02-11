<?php
/**
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Queues
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

namespace Ircbot\Handler;

/**
 * The queue handler
 */
class Queues
{
    private $_queues = array();
    private $_lastId = 0;
    public function addQueue(\Ircbot\Type\Queue $queue)
    {
        ++$this->_lastId;
        $this->_queues[$this->_lastId] = $queue;
        return $this->_lastId;
    }
    /**
     * @param int $queueId
     * @return \Ircbot\Type\Queue
     */
    public function &getQueueById($queueId)
    {
        return $this->_queues[$queueId];
    }
}
