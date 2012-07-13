<?php
/*
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Modules
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

namespace Ircbot\Handler;

/**
 * The module handler class
 */
class Module
{
    
    protected $eventHandler;
    private $_modules = array();

    public function __construct(\Ircbot\Handler\Events $eventHandler)
    {
        $this->eventHandler = $eventHandler;
    }

    public function addModuleByObject(&$module)
    {
        if ($module instanceof \Ircbot\Module\AModule) {
            foreach ($module->events as $key => $value) {
                if (is_numeric($key)) {
                    $event  = $value;
                    $method = $value;
                } else {
                    $event  = $key;
                    $method = $value;
                }
                $callback   = array($module, $method);
                $this->eventHandler->addEventCallback($event, $callback);
            }
        }
        $this->_modules[get_class($module)] = $module;
        return $this;
    }
    
    public function getModuleByName($moduleName)
    {
        return (isset($this->_modules[$moduleName]))
            ? $this->_modules[$moduleName] : false;
    }   
}
