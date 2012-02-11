<?php
namespace Ircbot\Handler;

class Identifiers
{
    private $_identifiers = array();
    public function set()
    {
        if (is_array(func_get_arg(0))) {
            $settings = func_get_arg(0);
            foreach ($settings as $setting => $value) {
                $this->set($setting, $value);
            }
        } else {
            $identifier = func_get_arg(0);
            $value = func_get_arg(1);
            $this->_identifiers[$identifier] = $value;
        }
        return $this;
    }
    public function  __set($identifier, $value)
    {
        $this->set($identifier, $value);
    }
    public function  __get($identifier)
    {
        return $this->_identifiers[$identifier];
    }
}
