<?php

namespace Ircbot\Parser;

class Mask
{

    public function __invoke($rawmask)
    {
        $mask = new \Ircbot\Entity\Irc\Mask;
        sscanf(
            $rawmask, '%[^!]!%[^@]@%s', $mask->nickname, $mask->ident,
            $mask->host
        );
        return $mask;
    }

}
