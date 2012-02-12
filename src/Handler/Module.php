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
    private $_modules = array();

    public function addModuleByObject(&$module)
    {
        $eventHandler = \Ircbot\Application::getInstance()->getEventHandler();
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
                $eventHandler->addEventCallback($event, $callback);
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
