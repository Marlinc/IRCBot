<?php
class IRCBot_Parsers_NameReply
{
    public function parseNames($rawData)
    {
        $names = explode(' ', substr(strrchr(trim($rawData), ':'), 1));
        $nameList = array();
        foreach ($names as $name) {
            $prefix = substr($name, 0, 1);
            $rank = $this->_getRankByPrefix($prefix);
            if ($rank) {
                $name = substr($name, 1);
            }
            $nameList[$name] = $rank;
        }
        return $nameList;
    }
    private function _getRankByPrefix($prefix)
    {
        if ($prefix == '@') {
            return CHAN_MODE_OP;
        } elseif ($prefix == '%') {
            return CHAN_MODE_HALFOP;
        } elseif ($prefix == '+') {
            return CHAN_MODE_VOICE;
        }
        return 0;
    }
}
