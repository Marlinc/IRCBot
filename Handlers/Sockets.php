<?php
/**
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Sockets
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The socket handler
 *
 * This handler is created to handle sockets
 * It can readlines from sockets if needed and then call a callback with the data
 */
class IRCBot_Handlers_Sockets
{
    /**
     * This variable will contain all the socket classes
     * @var array
     */
    private $_sockets = array();
    private $_lastId = 0;
    public function addSocket(IRCBot_Types_Socket $socket)
    {
        ++$this->_lastId;
        $socket->socketId = $this->_lastId;
        $this->_sockets[$this->_lastId] = $socket;
        return $this->_lastId;
    }
    /**
     * @param int $socketId
     * @return IRCBot_Types_Socket
     */
    public function getSocketById($socketId)
    {
        return $this->_sockets[$socketId];
    }
}
?>
