<?php
/**
 * The string utility file
 * 
 * PHP version 5
 * 
 * @category Net
 * @package  IRCBot
 * @author   Marlin Cremers <marlinc@mms-projects.net>
 * @license  http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link     https://github.com/Marlinc/IRCBot
 */

/**
 * The actual string utility class
 * 
 * @category Net
 * @package  IRCBot
 * @author   Marlin Cremers <marlinc@mms-projects.net>
 * @license  http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 * @link     https://github.com/Marlinc/IRCBot
 */
class IRCBot_Utilities_String
{
    static private $_tokens = array();
    static private $_tokenizeChr = 32;
    
    /**
     * Tokenizes a string 
     *  
     * @param string $string The string to tokenize
     * @param int    $chr    The ASCII character code to use when 
     * @param bool   $return Specify if it needs to return a array of tokens
     * 
     * @return void|array
     */
    static public function tokenize($string, $chr = 32, $return = false)
    {
        self::$_tokenizeChr = (is_int($chr)) ? chr($chr) : $chr;
        if (!$return) {
            self::$_tokens = explode(self::$_tokenizeChr, $string);
        } else {
            return explode(self::$_tokenizeChr, $string);
        }
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
            $tmpOne = '';
            for ($i = (int) $start; $i < $end; ++$i) {
                $token = (self::token($i)) ? self::token($i) : '';
                if ($token) {
                    $tmpOne .= (($tmpOne) ? self::$_tokenizeChr : '') . $token;
                }
            }
            return $tmpOne;
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
