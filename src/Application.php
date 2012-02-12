<?php
/**
 * The main application class
 * 
 * PHP version 5
 * 
 * @category Net
 * @package  IRCBot
 * @author   Marlin Cremers <marlinc@mms-projects.net>
 * @license  http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link     https://github.com/Marlinc/IRCBot
 */

namespace Ircbot;
 
require_once 'Application/Autoloader.php';

/**
 * The main IRCBot application object
 *
 * The main IRCBot application object contains all the other important objects
 * like the database, socket and module handlers
 * 
 * @category Net
 * @package  IRCBot
 * @author   Marlin Cremers <marlinc@mms-projects.net>
 * @license  http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link     https://github.com/Marlinc/IRCBot
 */
class Application
{
    private static $_instance = null;
    private $_handlers = array();
    private $_loop = null;
    private $_debugger = null;
    
    /**
     * The private constructor
     */
    private function  __construct()
    {

    }
    /**
     * Initialize the IRCBot application
     * 
     * @return void
     */
    private function _init()
    {
        new Application\Autoloader(__DIR__);
        $this->_debugger = new Application\Debug;
        $this->_handlers['events']        = new Handler\Events;
        $this->_handlers['signals']       = new Handler\Signals;
        $this->_handlers['modules']       = new Handler\Module;
        $this->_handlers['bots']          = new Handler\Bots;
        $this->_handlers['sockets']       = new Handler\Sockets;
        $this->_handlers['queues']        = new Handler\Queues;
        $this->_handlers['responses']     = new Handler\Responses;
        $this->_handlers['user_commands'] = new Handler\UserCommands;
        $this->_handlers['identifiers']   = new Handler\Identifiers;
        $this->_handlers['channels']      = new Handler\Channels;
        $this->_handlers['networks']      = new Handler\Networks;
        $this->_loop                      = new Application\Loop;
        $mainModule = new Module\Main;
        $this->getModuleHandler()->addModuleByObject($mainModule);
        $this->getEventHandler()->raiseEvent('ircbotInitialized');
    }
    /**
     * Return a instance of the IRCBot application
     * 
     * @return IRCBot_Application
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
            self::$_instance->_init();
        }
        return self::$_instance;
    }
    /**
     * Returns the event handler
     * 
     * @return IRCBot_Handlers_Events
     */
    public function getEventHandler()
    {
        return $this->_handlers['events'];
    }
    /**
     * Returns the module handler
     * 
     * @return IRCBot_Handlers_Modules
     */
    public function getModuleHandler()
    {
        return $this->_handlers['modules'];
    }
    /**
     * Returns the bot handler
     * 
     * @return IRCBot_Handlers_Bots
     */
    public function getBotHandler()
    {
        return $this->_handlers['bots'];
    }
    /**
     * Returns the socket handler
     * 
     * @return IRCBot_Handlers_Sockets
     */
    public function getSocketHandler()
    {
        return $this->_handlers['sockets'];
    }
    /**
     * Returns the queue handler
     * 
     * @return IRCBot_Handlers_Queues
     */
    public function getQueueHandler()
    {
        return $this->_handlers['queues'];
    }
    /**
     * Returns the response handler
     * 
     * @return IRCBot_Handlers_Commands
     */
    public function getResponseHandler()
    {
        return $this->_handlers['responses'];
    }
    /**
     * Returns the user command handler
     * 
     * @return IRCBot_Handlers_UserCommands
     */
    public function getUserCommandHandler()
    {
        return $this->_handlers['user_commands'];
    }
    /**
     * Returns the identifier handler
     * 
     * @return IRCBot_Handlers_Identifiers
     */
    public function getIdentifierHandler()
    {
        return $this->_handlers['identifiers'];
    }
    /**
     * Returns the channel handler
     * 
     * @return IRCBot_Handlers_Channels
     */
    public function getChannelHandler()
    {
        return $this->_handlers['channels'];
    }
    /**
     * Returns the network handler
     * 
     * @return IRCBot_Handlers_Networks
     */
    public function getNetworkHandler()
    {
        return $this->_handlers['networks'];
    }
    /**
     * Returns the loop class
     * 
     * @return IRCBot_Loop
     */
    public function getLoop()
    {
        return $this->_loop;
    }
    
    public function setDebugger($class)
    {
        if ($class instanceof Application\Debug\ADebug) {
            $this->_debugger = $class;
        } else {
            throw new \InvalidArgumentException('Expected a debugger class');
        }
    }
    
    /**
     * Returns the debugger class
     * 
     * @return IRCBot_Debugger_Abstract
     */
    public function getDebugger()
    {
        return $this->_debugger;
    }
}
