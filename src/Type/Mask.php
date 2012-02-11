<?php

namespace Ircbot\Type;

class Mask
{
    public $nickname;
    public $ident;
    public $host;
    
    public function __construct($mask = null)
    {
        if ($mask) {
            $this->fromMask($mask);
        }
    }
    public function fromMask($mask)
    {
        sscanf(
            $mask, '%[^!]!%[^@]@%s', $this->nickname, $this->ident, $this->host
        );
    }
    public function  __toString()
    {
        return sprintf('%s!%s@%s', $this->nickname, $this->ident, $this->host);
    }
}

