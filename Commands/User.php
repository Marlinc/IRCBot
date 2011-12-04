<?php
class IRCBot_Commands_User extends IRCBot_Types_Command
{
    /**
     * @var string
     */
    public $ident;
    /**
     * @var string
     */
    public $realName;
    public function  __construct($ident, $realName) {
        $this->ident = $ident;
        $this->realName = $realName;
    }
    public function  __toString() {
        return sprintf('USER %s * * :%s', $this->ident, $this->realName)
            . "\n\r";
    }
}
?>
