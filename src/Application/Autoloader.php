<?php

namespace Ircbot\Application;

class Autoloader
{

    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    public function autoload($class)
    {
        if (strstr($class, '\\')) {
            $classPath = explode('\\', $class);
            $package = array_shift($classPath);
            if ($package == 'Ircbot') {
                require_once implode(DIRECTORY_SEPARATOR, $classPath) . '.php';
            }
        }
    }

}
