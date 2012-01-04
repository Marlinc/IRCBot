<?php
/**
 * The loop class file
 * 
 * PHP version 5
 * 
 * @author  Marlin Cremers <marlinc@mms-projects.net>
 * @license http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link    https://github.com/Marlinc/IRCBot
 */

/**
 * The IRCBot loop
 *
 * The loop wich will maintain all the events and will handle various of events
 * 
 * @author  Marlin Cremers <marlinc@mms-projects.net>
 * @license http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link    https://github.com/Marlinc/IRCBot
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
     * 
     * @param int      $iterations The number of iterations to run
     * @param callback $onStart    The callback to call if the loop started
     * @param callback $onIterate  The callback to call every time to loop
     *                             iterates
     * 
     * @return int Returns the amount of iterations runned
     */
    public function startLoop($iterations = 0, $onStart = null, $onIterate = null)
    {
        if (!$onStart) {
            $onStart = array($this, 'onStart');
        }
        if (!$onInterate) {
            $onIterate = array($this, 'onIterate');
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
            call_user_func($onIterate, $this->iterations);
            usleep(25*1000);
        }
        return $this->iterations;
    }
    /**
     * Gets executed ones the loop is started
     * 
     * @return void
     */
    public function onStart()
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('loopStarted');
    }
    /**
     * Gets executed every time the loop iterates
     * 
     * @param int $iteration The amount of iterations done
     * 
     * @return void
     */
    public function onIterate($iteration)
    {
        IRCBot_Application::getInstance()->getEventHandler()
            ->raiseEvent('loopIterate', $iteration);
    }
}
?>
