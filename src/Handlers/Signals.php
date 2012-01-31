<?php
/**
 * @category IRCBot
 * @package IRCBot_Handlers
 * @subpackage Signals
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The signal handler class
 *
 * This signal handler class is made to send event when
 * specific signals are raised. The supported signals are:
 * SIGHUP
 * SIGTERM
 * SIGUSR1
 * SIGUSR2
 * SIGPWR
 * SIGINT
 */
class IRCBot_Handlers_Signals
{
    public function  __construct()
    {
        //echo "Installing signal handler...\n";
        pcntl_signal(SIGHUP, array($this, 'handleSignal'));
        pcntl_signal(SIGTERM, array($this, 'handleSignal'));
        pcntl_signal(SIGUSR1, array($this, 'handleSignal'));
        pcntl_signal(SIGUSR2, array($this, 'handleSignal'));
        pcntl_signal(SIGPWR, array($this, 'handleSignal'));
        pcntl_signal(SIGINT, array($this, 'handleSignal'));
        IRCBot_Application::getInstance()->getEventHandler()
            ->addEventCallback('loopIterate', array($this, 'getSignals'));
    }
    /**
     * This function will call the signal handlers for pending signals
     */
    public function getSignals()
    {
        pcntl_signal_dispatch();
    }
    public function handleSignal($signal)
    {
        //echo "signal handler called\n";
        if ($signal == SIGHUP) {
            $this->_raiseEvent('SIGHUP');
        } elseif ($signal == SIGTERM) {
            $this->_raiseEvent('SIGTERM');
        } elseif ($signal == SIGUSR1) {
            $this->_raiseEvent('SIGUSR1');
        } elseif ($signal == SIGUSR2) {
            $this->_raiseEvent('SIGUSR2');
        } elseif ($signal == SIGPWR) {
            $this->_raiseEvent('SIGPWR');
        } elseif ($signal == SIGINT) {
            $this->_raiseEvent('SIGINT');
        }
    }
    private function _raiseEvent($signalName)
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent($signalName);
    }
}
