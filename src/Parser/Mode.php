<?php

namespace Ircbot\Parser;

class Mode
{
    
    const MODE_SETARG    = 1;
    const MODE_UNSETARG  = 2;
    const MODE_NOARG     = 4;
    const MODE_ALWAYSARG = 3;
    
    const TYPE_SET       = 1;
    const TYPE_UNSET     = 2;
    
    const AREA_CHANNEL   = 1;
    const AREA_USER      = 2;
    
    public $modes = array(
        self::AREA_CHANNEL => array(
            /* Ranks */
    		'q' => self::MODE_ALWAYSARG,
    		'a' => self::MODE_ALWAYSARG,
            'o' => self::MODE_ALWAYSARG,
            'h' => self::MODE_ALWAYSARG,
            'v' => self::MODE_ALWAYSARG,
    		
            'm' => self::MODE_NOARG,
            'l' => self::MODE_SETARG,
            'k' => self::MODE_ALWAYSARG,
            'n' => self::MODE_NOARG,
            't' => self::MODE_NOARG,
        ),
        self::AREA_USER => array(
            'x' => self::MODE_NOARG,
        ),
    );
    
    public function __invoke($rawdata, $area = self::AREA_CHANNEL)
    {
        $debug = \Ircbot\Application::getInstance()->getDebugger();
        $data = array();
        $arguments = explode(' ', $rawdata);
        $argument = 0;
        for ($i = 0, $len = strlen($arguments[0]); $i < $len; ++$i) {
            $char = substr($arguments[0], $i, 1);
            if ($char == '+') {
                $type = self::TYPE_SET;
                $data[$type] = array();
            } elseif ($char == '-') {
                $type = self::TYPE_UNSET;
                $data[$type] = array();
            } else {
                if (!isset($this->modes[$area][$char])) {
                    $debug->log(
                        'parser', 'mode',
                        'Unknown ' . (($area == self::AREA_CHANNEL) ? 'channel'
                                      : 'user')
                            . ' mode \'' . $char . '\'' . PHP_EOL
                    );
                    continue;
                }
                $info = $this->modes[$area][$char];
                if (($type == self::TYPE_SET && $info & self::MODE_SETARG)
                    || ($type == self::TYPE_UNSET && $info & self::MODE_UNSETARG)
                ) {
                    ++$argument;
                    $tmp = $arguments[$argument];
                }
                $data[$type][] = array($char, (isset($tmp)) ? $tmp : true);
            }
        } 
        return $data;
    }
    
}