<?php
/**
 * The string utility file
 * 
 * PHP version 5
 * 
 * @author  Marlin Cremers <marlinc@mms-projects.net>
 * @license http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link    https://github.com/Marlinc/IRCBot
 */

/**
 * The actual string utility class
 * 
 * @author  Marlin Cremers <marlinc@mms-projects.net>
 * @license http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link    https://github.com/Marlinc/IRCBot
 */
class IRCBot_Utilities_String
{
    static private $_tokens = array();
    static private $_tokenizeChr = 32;
    
    /**
     * Tokenizes a string 
     *  
     * @param string $string The string to tokenize
     * @param int    $chr    The ASCII character code to use when tokenizing
     * 
     * @return void
     */
    static public function tokenize($string, $chr = 32)
    {
        self::$_tokenizeChr = (is_int($chr)) ? chr($chr) : $chr;
        self::$_tokens = explode(self::$_tokenizeChr, $string);
    }
    
    /**
     * Returns a specific token a tokenized string
     * 
     * @param string $token The token to return
     * 
     * @return string Returns the tokens
     */
    static public function token($token)
    {
        $token = (string) $token;
        if (ctype_digit($token)) {
            return (isset(self::$_tokens[(int) $token]))
                ? self::$_tokens[(int) $token] : '';
        }
        $tmp = explode('-', $token);
        if (count($tmp) == 2) {
            $start = (int) ($tmp[0]) ? $tmp[0] : 0;
            $end = (int) ($tmp[1]) ? ($tmp[1] + 1) : (count(self::$_tokens));
            if ($end < $start) {
                $start = $end;
            }
            $tmp1 = '';
            for ($i = (int) $start; $i < $end; ++$i) {
                $token = (self::token($i)) ? self::token($i) : '';
                if ($token) {
                    $tmp1 .= (($tmp1) ? self::$_tokenizeChr : '') . $token;
                }
            }
            return $tmp1;
        }
    }
    /**
     * Cleansup the tokenizer
     * 
     * @return void
     */
    static public function tokenizeCleanup()
    {
        self::$_tokenizeChr = 32;
        self::$_tokens = array();
    }
    /**
     * Removes newlines from the passed string
     * 
     * @param string $string The string to remove newlines from
     * 
     * @return string Returns a string without newlines 
     */
    static public function removeNewlines($string)
    {
        $string = trim(preg_replace("/[\n\r]/", null, $string));
        $string = explode(' ', $string);
        foreach ($string as $key => $value) {
            if (empty($value)) {
                unset($string[$key]);
            }
        }
        return implode(' ', $string);
    }
}
?>
