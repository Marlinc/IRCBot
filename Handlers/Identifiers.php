<?php
class IRCBot_Handlers_Identifiers
{
    private $_identifiers = array();
    public function set($identifier, $value)
    {
        $this->_identifiers[$identifier] = $value;
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
?>
