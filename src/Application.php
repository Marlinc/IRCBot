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

require_once 'Handlers/Events.php';
require_once 'Handlers/Signals.php';
require_once 'Handlers/Modules.php';
require_once 'Handlers/Bots.php';
require_once 'Handlers/Sockets.php';
require_once 'Handlers/Queues.php';
require_once 'Handlers/Responses.php';
require_once 'Handlers/UserCommands.php';
require_once 'Handlers/Identifiers.php';
require_once 'Handlers/Channels.php';
require_once 'Handlers/Networks.php';
require_once 'Parsers/Commands.php';
require_once 'Parsers/NameReply.php';
require_once 'Types/Socket.php';
require_once 'Types/Bot.php';
require_once 'Types/Queue.php';
require_once 'Types/Response.php';
require_once 'Types/Command.php';
require_once 'Types/Numeric.php';
require_once 'Types/MessageCommand.php';
require_once 'Types/Channel.php';
require_once 'Types/Mask.php';
require_once 'Types/Topic.php';
require_once 'Types/NameReply.php';
require_once 'Types/ISupport.php';
require_once 'Types/Network.php';
require_once 'Commands/Notice.php';
require_once 'Commands/Ping.php';
require_once 'Commands/Pong.php';
require_once 'Commands/Nick.php';
require_once 'Commands/User.php';
require_once 'Commands/Quit.php';
require_once 'Commands/PrivMsg.php';
require_once 'Commands/Join.php';
require_once 'Commands/Part.php';
require_once 'Commands/Topic.php';
require_once 'Commands/Error.php';
require_once 'Commands/Invite.php';
require_once 'Commands/Mode.php';
require_once 'Modules/Main.php';
require_once 'Utilities/String.php';
require_once 'Loop.php';
require_once 'Debugger.php';
require_once 'shortFunctions.php';

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
class IRCBot_Application
{
    private static $_instance = null;
    private $_handlers = array();
    private $_parsers = array();
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
        $this->_debugger = new IRCBot_Debugger();
        $this->_handlers['events'] = new IRCBot_Handlers_Events();
        $this->_handlers['signals'] = new IRCBot_Handlers_Signals();
        $this->_handlers['modules'] = new IRCBot_Handlers_Modules();
        $this->_handlers['bots'] = new IRCBot_Handlers_Bots();
        $this->_handlers['sockets'] = new IRCBot_Handlers_Sockets();
        $this->_handlers['queues'] = new IRCBot_Handlers_Queues();
        $this->_handlers['responses'] = new IRCBot_Handlers_Responses();
        $this->_handlers['user_commands'] = new IRCBot_Handlers_UserCommands();
        $this->_handlers['identifiers'] = new IRCBot_Handlers_Identifiers();
        $this->_handlers['channels'] = new IRCBot_Handlers_Channels();
        $this->_handlers['networks'] = new IRCBot_Handlers_Networks();
        $this->_parsers['commands'] = new IRCBot_Parsers_Commands();
        $this->_loop = new IRCBot_Loop();
        $mainModule = new IRCBot_Modules_Main();
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
            self::$_instance = new IRCBot_Application();
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
     * Returns the command parser
     * 
     * @return IRCBot_Parsers_Commands
     */
    public function getCommandParser()
    {
        return $this->_parsers['commands'];
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
    /**
     * Returns the debugger class
     * 
     * @return IRCBot_Debugger
     */
    public function getDebugger()
    {
        return $this->_debugger;
    }
}
?>
