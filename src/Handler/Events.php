<?php
/**
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Events
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

namespace Ircbot\Handler;

use \Ircbot\Application\Debug;

/**
 * The IRCBot event handler
 *
 * This is the callback driven IRCBot event handler
 * you can simply raise any event by calling the raiseEvent method
 * or register a callback with any event by calling the addEventCallback method
 */
class Events
{
    /**
     * This variable contains all the callbacks registered with the events
     * @access private
     * @var array
     */
    private $_callbacks = array();
    /**
     * Raises the specific event with the data if given
     * @param mixed $eventName The name of the event to be raised or a
     * array of names
     * @param mixed $data The data send with the raised event
     */
    public function raiseEvent($eventName, $data = null)
    {
        if (is_array($eventName)) {
            foreach ($eventName as $name) {
                $this->raiseEvent($name, $data);
            }
        } else {
            if ($eventName != 'loopIterate') {
                \Ircbot\Application::getInstance()->getDebugger()->log(
                    'Events', 'RaisedEvent', $eventName, Debug::LEVEL_INFO
                );
            }
            foreach ($this->_callbacks as $callback) {
                if ($callback['eventName'] == $eventName) {
                    call_user_func($callback['callback'], $data);
                }
            }
        }
    }
    /**
     * Registers a callback with the specific event
     * @param string $eventName
     * @param callback $callback 
     */
    public function addEventCallback($eventName, $callback)
    {
        $debugger = \Ircbot\Application::getInstance()->getDebugger();
        $tmp = array();
        $tmp['eventName'] = $eventName;
        $tmp['callback'] = $callback;
        $this->_callbacks[] = $tmp;
        if (is_array($callback)) {
            $callbackDisplay = get_class($callback[0]) . '::' . $callback[1];
            if (!$callback[0] instanceof \Ircbot\Module\AModule) {
                $debugger->log(
                    'Events', 'Warning', 'Trying to add a callback to a non '
                        . 'module class', Debug::LEVEL_WARN
                );
            }
        } else {
            $callbackDisplay = $callback;
        }
        if (!is_callable($callback)) {
            throw new \Exception('Invalid callback');
        }
        $debugger->log(
            'Events', 'AddCallback', $eventName . ' => ' . $callbackDisplay,
            Debug::LEVEL_DEBUG
        );
        return $this;
    }
}
