<?php
/*
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Modules
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The module handler class
 */
class IRCBot_Handlers_Modules
{
    private $_modules = array();

    public function addModuleByObject(&$module)
    {
        $this->_modules[get_class($module)] = $module;
        return $this;
    }
    public function getModuleByName($moduleName)
    {
        return (isset($this->_modules[$moduleName]))
            ? $this->_modules[$moduleName] : false;
    }   
}
