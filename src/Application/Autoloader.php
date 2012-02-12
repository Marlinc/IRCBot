<?php

namespace Ircbot\Application;

class Autoloader
{

    public $applicationPath;

    public function __construct($path)
    {
        $this->applicationPath = $path;
        spl_autoload_register(array($this, 'autoload'));
    }

    public function autoload($class)
    {
        if (strstr($class, '\\')) {
            $classPath = explode('\\', $class);
            $package = array_shift($classPath);
            array_unshift($classPath, $this->applicationPath);
            if ($package == 'Ircbot') {
                require_once implode(DIRECTORY_SEPARATOR, $classPath) . '.php';
            }
        }
    }

}
