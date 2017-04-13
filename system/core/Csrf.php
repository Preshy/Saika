<?php

/**
* Saika - The PHP Framework for KIDS
*
* CSRF protection class for Saika.
*
* @version 1.0
* @since 1.0
*/
class Csrf
{
    /**
     * The session key for storing csrf token
     *
     * @var string
     */
    public static $csrf_token_key = '__saika_csrf_token';

    /**
     * The session key for storing token expiration time
     *
     * @var string
     */
    public static $csrf_token_time = '__saika_csrf_token_time';

    /**
     * Get the CSRF Token
     *
     * @return string
     */
    public static function getToken()
    {
        $max_time = (int)Config::get('CSRF_TOKEN_LIFETIME');
        $stored_time = Session::get(self::$csrf_token_time);
        $csrf_token  = Session::get(self::$csrf_token_key);

        if ($max_time + $stored_time <= time() || empty($csrf_token)) {
            Session::set(self::$csrf_token_key, get_secure_token(32));
            Session::set(self::$csrf_token_time, time());
        }

        return (string)Session::get(self::$csrf_token_key);
    }

    /**
     * Get the CSRF Token, in HTML hidden input field
     *
     * @param string $input_name The name of the input field
     *                           (Optional) Default is self::$csrf_token_key
     *                           by default configuration value is used.
     * @return string
     */
    public static function getTokenHtml($input_name = '')
    {
        if ($input_name === '')
            $input_name = self::$csrf_token_key;
        // Well, just to be safe!
        $input_name = filter_var($input_name, FILTER_SANITIZE_STRING);
        $token = self::getToken();
        $input_name = (string)$input_name;

        return '<input type="hidden" name="'.$input_name.'" value="'.$token.'">';
    }

    /**
     * Verify the CSRF token against CSRF token submitted via form
     *
     * @param string $input CSRF token submitted via form
     * @return boolean
     */
    public static function verify($input = '')
    {
        $input = (string)$input;
        return $input === Session::get(self::$csrf_token_key) && !empty($input);
    }

    /**
     * Force reset the CSRF token
     *
     * @return
     */
    public static function reset()
    {
        // Remove the value from session
        Session::remove(self::$csrf_token_key);
        // Generate again! -_-
        self::getToken();
    }
}
