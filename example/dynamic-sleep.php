<?php
require_once __DIR__ . '/../src/Application.php';

Ircbot\Application::getInstance();

class SleepTest extends \Ircbot\Module\AModule
{

    public $events = array(
        'loopIterate',
        'SIGUSR1' => 'SigUserOne',
    );
    
    public $lastCheck = 0;
    
    public function loopIterate($tick)
    {
        $maxAllowed = 3;
        $threshold  = 0.2;
        if (($tick - $this->lastCheck) > 50) {
            $data = exec('ps u -p ' . getmypid());
            sscanf($data, '%*s %*d %s', $usage);
            $usage = (float) $usage;
            if ($usage < $maxAllowed - $threshold) {
                $change = ($maxAllowed - $usage) * 1000;
                Ircbot\Application::getInstance()->getLoop()->sleepTime -= $change;
                if (Ircbot\Application::getInstance()->getLoop()->sleepTime < 1001) {
                    Ircbot\Application::getInstance()->getLoop()->sleepTime = 1000;
                }
            }
            if ($usage > $maxAllowed + $threshold) {
                $change = ($usage - $maxAllowed) * 1000;
                Ircbot\Application::getInstance()->getLoop()->sleepTime += $change;
            }
            $this->lastCheck = $tick;
            $this->usage = $usage;
        }
        echo $tick . ' - ' . Ircbot\Application::getInstance()->getLoop()->sleepTime . ' - ' . $this->usage . PHP_EOL;
    }
    
    public function SigUserOne()
    {
        Ircbot\Application::getInstance()->getLoop()->sleepTime -= 5000;
    }

}

