<?php
define('TYPE_CHANMSG', 1);
define('TYPE_PRIVMSG', 2);
define('TYPE_CHANNOTICE', 4);
define('TYPE_PRIVNOTICE', 8);
define('TYPE_MSG', TYPE_CHANMSG | TYPE_PRIVMSG);
define('TYPE_NOTICE', TYPE_CHANNOTICE | TYPE_CHANNOTICE);

define('IRCBOT_USERCMD_SCANTYPE_STATIC', 1);
define('IRCBOT_USERCMD_SCANTYPE_WILDCRD', 2);
define('IRCBOT_USERCMD_SCANTYPE_REGEX', 4);

class IRCBot_Handlers_UserCommands
{
    public $defaultScanType = IRCBOT_USERCMD_SCANTYPE_WILDCRD;
    public $defaultMsgType = TYPE_CHANMSG;
    private $_callbacks = array();

    public function  __construct()
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->addEventCallback('onPrivMsg', array($this, 'onMsg'))
            ->addEventCallback('onNotice', array($this, 'onMsg'));
    }
    public function addCommand($callback, $message, $msgType = null,
        $scanType = null, $extraInfo = null)
    {
        $tmp = array();
        $tmp['callback'] = $callback;
        $tmp['message'] = $message;
        $tmp['type'] = ($msgType) ? $msgType : $this->defaultMsgType;
        $tmp['scan_type'] = ($scanType) ? $scanType : $this->defaultScanType;
        $tmp['extra_info'] = $extraInfo;
        $this->_callbacks[] = $tmp;
        return $this;
    }
    public function onMsg($event)
    {
        $isChan = (substr($event->target, 0, 1) == '#');
        $type = null;
        if ($event instanceof IRCBot_Commands_PrivMsg) {
            $type = ($isChan) ? TYPE_CHANMSG : TYPE_PRIVMSG;
        } elseif ($event instanceof IRCBot_Commands_Notice) {
            $type = ($isChan) ? TYPE_CHANNOTICE : TYPE_PRIVNOTICE;
        }
        foreach ($this->_callbacks as $callback) {
            if (!($callback['type'] & $type)) {
                continue;
            }
            if ($callback['scan_type'] == IRCBOT_USERCMD_SCANTYPE_STATIC) {
                if ($callback['message'] != $event->message) {
                    continue;
                }
            }
            if ($callback['scan_type'] == IRCBOT_USERCMD_SCANTYPE_WILDCRD) {
                if (!fnmatch($callback['message'], $event->message)) {
                    continue;
                }
            }
            if ($callback['scan_type'] == IRCBOT_USERCMD_SCANTYPE_REGEX) {
                if (!preg_match(
                    $callback['message'], $event->message, $event->matches
                )) {
                    continue;
                }
            }
            if ($callback['extra_info']) {
                $data = array($event, $callback['extra_info']);
            } else {
                $data = $event;
            }
            call_user_func($callback['callback'], $data);
        }
    }
    public function setDefaultMsgType($msgType)
    {
        $this->defaultMsgType = $msgType;
        return $this;
    }
    public function setDefaultScanType($scanType)
    {
        $this->defaultScanType = $scanType;
        return $this;
    }
}
