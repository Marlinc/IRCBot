<?php
class IRCBot_Handlers_Channels
{
    private $_channels = array();

    /**
     * @param string $channelName The name of the channel to search for
     * @return IRCBot_Types_Channel
     */
    public function getChan($channelName, $botId)
    {
        if (!isset($this->_channels[$botId])) {
            $this->_channels[$botId] = array();
        }
        foreach ($this->_channels[$botId] as $channel) {
            /* @var $channel IRCBot_Types_Channel */
            if (strtolower($channel->name) == strtolower($channelName)) {
                return $channel;
            }
        }
        return null;
    }
    public function addChan($channelName, $botId)
    {
        if (empty($channelName)) {
            throw new Exception(
                'Tried to add a channel but no name was given.'
            );
        }
        $chan = new IRCBot_Types_Channel();
        $chan->name = $channelName;
        $this->_channels[$botId][] = $chan;
        return $chan;
    }
    public function delChan($channelName, $botId)
    {
        foreach ($this->_channels[$botId] as $key => $channel) {
            /* @var $channel IRCBot_Types_Channel */
            if (strtolower($channel->name) == strtolower($channelName)) {
                unset($this->_channels[$botId][$key]);
            }
        }
    }
}
