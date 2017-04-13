<?php

/**
 * Saika - The PHP Framework For KIDS
 *
 * The Request Wrapper Class.
 *
 * @version 1.0
 * @since 1.0
 */

class Request
{

    /**
     * Gets/returns the value of a specific key of the GET super-global.
     *
     * @param  mixed  $key     The Key
     * @param  mixed $default Fall-back value
     * @return mixed
     */
    public static function get($key, $default = false)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    /**
     * Gets/returns the value of a specific key of the POST super-global.
     *
     * @param  mixed  $key     The Key
     * @param  mixed $default Fall-back value
     * @return mixed
     */
    public static function post($key, $default = false)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    /**
     * Gets/returns the value of a specific key of the SERVER super-global.
     *
     * @param  mixed  $key     The Key
     * @param  mixed $default Fall-back value
     * @return mixed
     */
    public static function server($key, $default = false)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        return $default;
    }


    /**
     * Checks if the current request was sent
     * with a XMLHttpRequest header as sent by javascript
     *
     * @return boolean
     */
    public static function isAjax()
    {
        if (!self::server('HTTP_X_REQUESTED_WITH'))
            return false;
        return strcasecmp(mb_strtolower(self::server('HTTP_X_REQUESTED_WITH')), 'xmlhttprequest') === 0;
    }

}
