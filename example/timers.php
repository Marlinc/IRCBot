<?php

require_once __DIR__ . '/../src/Application.php';

$ircbot = Ircbot\Application::getInstance();

function timerOne(\Ircbot\Entity\Timer $timer)
{
    echo $timer->getName() . PHP_EOL;
}

function timerTwo(\Ircbot\Entity\Timer $timer)
{
    echo $timer->getName() . PHP_EOL;
}

Ircbot\Application::getInstance()->getEventHandler()->addListener(
    'loop.started', function ($event) {
        $timer1 = new \Ircbot\Entity\Timer('timer1', 'timerOne', 2000);
        $timer1->maxIterations = 2;
        $timer2 = new \Ircbot\Entity\Timer('timer2', 'timerTwo', 1000);
        \Ircbot\Handler\Timer::addTimer($timer1);
        \Ircbot\Handler\Timer::addTimer($timer2);
    }
);


$ircbot->getLoop()->startLoop();

?>
