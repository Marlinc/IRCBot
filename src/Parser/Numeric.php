<?php

namespace Ircbot\Parser;

class Numeric
{

    public function __invoke($rawdata)
    {
        $tmp = explode(' ', $rawdata);
        if ($tmp[1] == RPL_NAMREPLY) {
            $parser  = new \Ircbot\Parser\NameReply;
            $numeric = new \Ircbot\Numeric\NameReply();
            $numeric->names = $parser($rawdata);
            list(,,,, $numeric->channel) = explode(' ', $rawdata);
        } elseif ($tmp[1] == RPL_ISUPPORT) {
            $numeric = new \Ircbot\Numeric\ISupport();
        } else {
            $numeric = new \Ircbot\Numeric\Numeric();
        }
        sscanf(
            $rawdata, ':%s %s %s %[ -~]', $numeric->server, $numeric->numeric,
            $numeric->target, $numeric->message
        );
        return $numeric;
    }

}
