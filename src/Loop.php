<?php
/**
 * @category IRCBot
 * @package IRCBot
 * @subpackage Loop
 * @author Marlin Cremers <marlinc@mms-projects.net>
 */

/**
 * The IRCBot loop
 *
 * The loop wich will maintain all the events and will handle various of events
 */
class IRCBot_Loop
{
    /**
     * The number number iterations
     * @var int
     */
    public $iterations = 0;
    /**
     * This method will start the infinite loop
     */
    public function startLoop($iterations = 0, $onStart = null, $onInterate = null)
    {
        if (!$onStart) {
            $onStart = array($this, 'onStart');
        }
        if (!$onInterate) {
            $onInterate = array($this, 'onIterate');
        }
        call_user_func($onStart);
        $condition = true;
        while ($condition) {
            ++$this->iterations;
            if (!empty($iterations)) {
                $condition = ($iterations > $this->iterations);
            } else {
                $condition = true;
            }
            call_user_func($onInterate, $this->iterations);
            usleep(25*1000);
        }
        return $this->iterations;
    }
    public function onStart()
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('loopStarted');
    }
    public function onIterate($iteration)
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('loopIterate', $iteration);
    }
}
?>
