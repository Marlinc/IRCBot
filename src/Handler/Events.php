<?php
namespace Ircbot\Handler;

use \Ircbot\Application\Debug;
use \Symfony\Component\EventDispatcher\Event;

/**
 * The IRCBot event handler
 *
 * This is the callback driven IRCBot event handler
 * you can simply raise any event by calling the raiseEvent method
 * or register a callback with any event by calling the addEventCallback method
 */
class Events extends \Symfony\Component\EventDispatcher\EventDispatcher
{
    /**
     * This variable contains all the callbacks registered with the events
     * @access private
     * @var array
     */
    private $_callbacks = array();
    protected $debugger;
    
    public function __construct(\Ircbot\Application\Debug $debugger)
    {
        $this->debugger = $debugger;
    }
    
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
                $this->debugger->log(
                    'Events', 'RaisedEvent', $eventName, Debug::LEVEL_INFO
                );
                /*$this->debugger->log(
                    'Events', 'Event', 'WARNING  - This method is deprecated! '
                        . 'Use dispatch instead. (' . $eventName . ')',
                    Debug::LEVEL_WARN
                );*/
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
        $tmp = array();
        $tmp['eventName'] = $eventName;
        $tmp['callback'] = $callback;
        $this->_callbacks[] = $tmp;
        if (is_array($callback)) {
            $callbackDisplay = get_class($callback[0]) . '::' . $callback[1];
            if (!$callback[0] instanceof \Ircbot\Module\AModule) {
                $this->debugger->log(
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
        $this->debugger->log(
            'Events', 'AddCallback', $eventName . ' => ' . $callbackDisplay,
            Debug::LEVEL_DEBUG
        );
        /*$this->debugger->log(
            'Events', 'Callback', 'WARNING  - This method is deprecated! '
                . 'Use addListener instead. (' . $eventName . ')',
            Debug::LEVEL_WARN
        );*/
        return $this;
    }
    
    public function addListener($eventName, $listener, $priority = 0)
    {
        if (!$this->hasListeners($eventName)) {
            $this->dispatch('eventdispatcher.event_used.' . $eventName);
        }
        parent::addListener($eventName, $listener, $priority);
    }
    
    protected function doDispatch($listeners, $eventName, Event $event)
    {
        if ($eventName != 'loop.iterated') {
            $this->debugger->log(
                'Symfony', 'EventDispatcher', 'Dispatched event ' . $eventName,
                Debug::LEVEL_INFO
            );
        }
        parent::doDispatch($listeners, $eventName, $event);
    }
    
}
