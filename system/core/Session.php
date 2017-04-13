<?php

/**
* Saika - The PHP Framework for KIDS
*
* The Session Wrapper Class
*
* @version 1.0
* @since 1.0
*/

class Session
{

    /**
     * Start the session if not started already
     *
     * @return
     */
    public static function start()
    {
        if (session_id() == '') {
            session_start();
        }
    }

    /**
     * Close the session
     *
     * @return
     */
    public static function close()
    {
        session_write_close();
    }

    /**
     * Set a key, value to session array
     *
     * @param mixed $key   The Key
     * @param mixed $value The Value
     */
    public static function set($key, $value = '')
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a value from session array by its key
     *
     * @param  mixed  $key     The key
     * @param  mixed  $default Fall-back/default value to return
     * @return mixed
     */
    public static function get($key, $default = false)
    {
        if (isset($_SESSION[$key]))
            return $_SESSION[$key];

        return $default;
    }

    /**
     * Unset a session key
     *
     * @param  mixed $key The key
     * @return
     */
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }


    /**
     * adds a value as a new array element to the key.
     * useful for collecting error messages etc
     *
     * @param mixed $key
     * @param mixed $value
     */
    public static function add($key, $value)
    {
        $_SESSION[$key][] = $value;
    }

    /**
     * Destroy the session completely
     *
     * @return
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * Regenerate the session ID
     *
     * @param boolean $destroy Whether to destroy the session or not.
     *                         Defaults to FALSE
     * @return
     */
    public static function regenerate($destroy = false)
    {
        session_regenerate_id($destroy);
    }
}
