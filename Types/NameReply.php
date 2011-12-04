<?php
class IRCBot_Types_NameReply extends IRCBot_Types_Numeric {
    public $names;
    public $channel;
    
    public function fromRawData($rawData) {
        parent::fromRawData($rawData);
        $parser = new IRCBot_Parsers_NameReply;
        $this->names = $parser->parseNames($rawData);
        list(,,,, $this->channel) = explode(' ', $rawData);
        
    }
}
