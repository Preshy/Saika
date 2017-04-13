<?php

/**
 * Saika - The PHP Framework for KIDS
 *
 * Config file reader class.
 *
 * @version 1.0
 * @since 1.0
 */

class Config
{
    public static $config;

    /**
     * Retrieve a config item value
     *
     * @param  mixed  $key     The Config Item Key
     * @param  mixed  $default  Fall-back value, will be return it the key doesn't exist
     *                          Defaults to FALSE
     * @return mixed
     */
    public static function get($key, $default = false)
    {
        if (!self::$config) {

            $config_file = APP. 'config/Saika.config.php';

            if (!file_exists($config_file)) {
                throw new \Exception('Fatal Error! No Configurtion file found at '.$config_file);
            }

            self::$config = require $config_file;
        }

        return isset(self::$config[$key]) ? self::$config[$key] : $default;
    }
}
