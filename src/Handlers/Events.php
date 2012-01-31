<?php
/**
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Events
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The IRCBot event handler
 *
 * This is the callback driven IRCBot event handler
 * you can simply raise any event by calling the raiseEvent method
 * or register a callback with any event by calling the addEventCallback method
 */
class IRCBot_Handlers_Events
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
                IRCBot_Application::getInstance()->getDebugger()
                    ->log('Events', 'RaisedEvent', $eventName);
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
        } else {
            $callbackDisplay = $callback;
        }
        IRCBot_Application::getInstance()->getDebugger()
            ->log(
                'Events', 'AddCallback', $eventName . ' => ' . $callbackDisplay
            );
        return $this;
    }
}
