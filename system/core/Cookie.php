<?php

/**
* Saika - The PHP Framework for KIDS
*
* The Cookie Wrapper Class
*
* @version 1.0
* @since 1.0
*/

class Cookie
{

    /**
     * Set a cookie
     *
     * @param mixed $name      The name of the cookie.
     *
     * @param string $value    (optional) The value of the cookie.
     *
     * @param integer $expire  (optional) Specifies when the cookie expires,
     *                         by default the Config value will be used.
     *
     * @param string $path     (optional) Specifies the server path of the cookie,
     *                         by default the Config value will be used.
     * @param string $domain   (optional) Specifies the domain name of the cookie,
     *                         by default the Config value will be used.
     *
     * @param boolean $secure  (optional) Specifies whether or not the cookie
     *                         should only be transmitted over a secure HTTPS
     *                         connection.
     *                         By default the Config value will be used.
     *
     * @param boolean $httponly (optional) If set to TRUE the cookie will be accessible
     *                           only through the HTTP protocol.
     *                           By default the Config value will be used.
     * @throws \Exception If headers sent already.
     * @return boolean
     */
    public static function set($name, $value = '', $expire = '',
       $path = '', $domain ='', $secure = '', $httponly = '')
    {
        // Sorry Watson!
        if (headers_sent())
            throw new \Exception('Failed to set cookie. Headers already
                sent!');

        // Set default expire time if not defined
        if ($expire === '')
            $expire = Config::get('COOKIE_LIFETIME');

        // Set default cookie path if not defined
        if ($path === '')
            $path = Config::get('COOKIE_PATH');

        // Set default cookie domain if not defined
        if ($domain === '')
            $domain = Config::get('COOKIE_DOMAIN');

        // Set default ssl only cookie status if not defined
        if ($secure === '')
            $secure = Config::get('COOKIE_SECURE');

        // Set default http only cookie status if not defined
        if ($httponly === '')
            $httponly = Config::get('COOKIE_HTTPONLY');

        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);

    }

    /**
     * Get a cookie value by its key/name
     *
     * @param  mixed  $name    The cookie key/name
     * @param  mixed $default Fall-back value
     * @return mixed
     */
    public static function get($name, $default = false)
    {
        if (isset($_COOKIE[$name]))
            return $_COOKIE[$name];

        return $default;
    }

    /**
     * Remove a Cookie
     *
     * @param  mixed $name The cookie name
     * @throws \Exception If headers sent already.
     * @return
     */
    public static function remove($name)
    {
         // Sorry Watson!
        if (headers_sent())
            throw new \Exception('Failed to remove cookie. Headers already
                sent!');
        setcookie($name, "", 1);
        setcookie($name, false);
        unset($_COOKIE[$name]);
    }

    /**
     * Flush all cookies ( including sessions )
     *
     * @return boolean
     */
    public static function flush()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);

            if (empty($cookies))
                return false;

            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                self::remove($name);
            }
            return true;
        }

        return false;
    }

}
