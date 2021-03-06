<?php
namespace Ircbot\Handler;

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
class Signals
{

    public function  __construct()
    {
        $this->addListener('SIGHUP');
        $this->addListener('SIGTERM');
        $this->addListener('SIGUSR1');
        $this->addListener('SIGUSR2');
        $this->addListener('SIGPWR');
        $this->addListener('SIGINT');
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
    
    public function activateSignalListen($event)
    {
        $this->listenOnSignal(end(explode('.', $event->getName())));
    }
    
    private function _raiseEvent($signalName)
    {
        \Ircbot\Application::getInstance()->getEventHandler()
            ->raiseEvent($signalName);
        \Ircbot\Application::getInstance()->getEventHandler()
            ->dispatch('signal.' . $signalName);
    }
    
    protected function addListener($signal)
    {
        \Ircbot\Application::getInstance()->getEventHandler()->addListener(
            'eventdispatcher.event_used.signal.' . $signal,
            array($this, 'activateSignalListen')
        );
    }
        
    protected function listenOnSignal($signal)
    {
        pcntl_signal(constant($signal), array($this, 'handleSignal'));
        \Ircbot\Application::getInstance()->getDebugger()->log(
            'Signals', 'Listening', 'Now listening on signal ' . $signal
        );
    }

}
