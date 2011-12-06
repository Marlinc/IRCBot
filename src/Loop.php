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
    public function startLoop()
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('loopStarted');
        while (true) {
            ++$this->iterations;
            IRCBot_Application::getInstance()->getEventHandler()
                ->raiseEvent('loopIterate', $this->iterations);
            usleep(25*1000);
        }
    }
}
?>
