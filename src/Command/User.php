<?php

namespace Ircbot\Command;

class User extends \Ircbot\Type\Command
{
    /**
     * @var string
     */
    public $ident;
    /**
     * @var string
     */
    public $realName;
    public function  __construct($ident, $realName)
    {
        $this->ident = $ident;
        $this->realName = $realName;
    }
    public function  __toString()
    {
        return sprintf('USER %s * * :%s', $this->ident, $this->realName)
            . "\n\r";
    }
}

