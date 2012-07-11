<?php
require_once __DIR__ . '/../src/Application.php';
require_once __DIR__ . '/../src/shortFunctions.php';

use \Ircbot\Command\PrivMsg;
use \Ircbot\Command\Notice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

$ircBot = Ircbot\Application::getInstance();

class MyBot extends \Ircbot\Module\AModule implements EventSubscriberInterface
{

    static public function getSubscribedEvents()
    {
        return array(
            'loop.started'  => array('onLoopStarted', 0),
            'irc.connected' => array('onConnect', 0),
        );
    }

    public function  __construct() {
        \Ircbot\Application::getInstance()->getEventHandler()
            ->addSubscriber($this);
        \Ircbot\Application::getInstance()->getUserCommandHandler()
            ->setDefaultMsgType(TYPE_CHANMSG)
            ->addCommand(array($this, 'onHelloWorld'), 'Hello World!')
            ->addCommand(array($this, 'onSay'), '!say*')
            ->addCommand(array($this, 'onExec'), '!exec*')
            ->setDefaultMsgType(TYPE_PRIVNOTICE)
            ->addCommand(array($this, 'onHelp'), 'help')
            ->setDefaultMsgType(TYPE_CHANMSG)
            ->setDefaultScanType(IRCBOT_USERCMD_SCANTYPE_REGEX)
            ->addCommand(array($this, 'onTest'), '/^[!@.]test$/');
    }
    public function onLoopStarted()
    {
        $bot = new \Ircbot\Type\Bot;
        $bot->nickname = 'PHPIRCBOT' . mt_rand();
        $bot->nicknameAlternative = $bot->nickname . '_';
        $bot->connect('irc.quakenet.org');
        \Ircbot\Application::getInstance()->getBotHandler()->addBot($bot);
    }

    public function onConnect($event)
    {
        
        \Ircbot\joinChan('#PHPIRCBot', $event->getBotId());
    }

    public function onHelloWorld(PrivMsg $msg)
    {
        \Ircbot\msg($msg->target, 'Hi ' . $msg->mask->nickname . '!');
        $bot = \Ircbot\getBotById(\Ircbot\botId());
        $bot->sendRawData(new \Ircbot\Command\CtcpRequest($msg->mask->nickname, 'VERSION'));
    }

    public function onSay(PrivMsg $msg)
    {
        \Ircbot\tokenize($msg->message);
        \Ircbot\msg($msg->target, \Ircbot\token('1-'));
    }

    public function onExec(PrivMsg $msg)
    {
        \Ircbot\tokenize($msg->message);
        \Ircbot\msg($msg->target, eval(\Ircbot\token('1-')));
    }

    public function onHelp(Notice $notice)
    {
        \Ircbot\notice($notice->mask->nickname, 'Sorry I can\'t help you^^');
    }

    public function onTest(PrivMsg $msg) {
        \Ircbot\notice(nick(), token('0-'));
    }

}

$ircBot->getModuleHandler()->addModuleByObject(new MyBot);
$ircBot->getLoop()->startLoop();
