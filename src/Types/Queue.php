<?php
class IRCBot_Types_Queue
{
    private $_queue = array();

    public function getFirstEntry()
    {
        reset($this->_queue);
        $data = current($this->_queue);
        return ($data != '') ? $data : false;
    }
    public function delFirstEntry()
    {
    reset($this->_queue);
    $data = each($this->_queue);
    unset($this->_queue[$data['key']]);
    return $this;
    }
    public function shift()
    {
    return array_shift($this->_queue);
    }
    public function addEntry($entry)
    {
    $this->_queue[] = $entry;
    return $this;
    }
    public function addEntries(array $entries)
    {
    foreach ($entries as $entry) {
    $this->addEntry($entry);
    }
    return $this;
    }
    public function getEntryCount()
    {
    return count($this->_queue);
    }
    public function clean()
    {
    $this->_queue = array();
    }
    public function isEmpty()
    {
    return (count($this->_queue) == 0) ? true : false;
    }
}
?>
