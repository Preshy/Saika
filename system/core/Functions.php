<?php

/**
 * Saika - The PHP Framework for KIDS
 *
 * Core Functions - Functional Programming isn't that bad! :3
 * Most of 'em are forked from world wide web.
 *
 * IMPORTANT: The functions here are the part of Saika's core. They are used by
 * Core classes of Saika, so you better not touch them.
 *
 * @version 1.0
 * @since 1.0
 */

//=========================================================
// Core saika functions
//=========================================================

if (!function_exists('saika_detect_url')) {
    /**
     * Detects site URL
     *
     * @return string
     */
    function saika_detect_url()
    {
        $protocol = is_https() ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $script = str_replace(basename($_SERVER['SCRIPT_NAME']), '' ,$_SERVER['SCRIPT_NAME']);
        return $protocol . $host . $script;
    }
}


if (!function_exists('site_url')) {
    /**
     * Quick access the Site URL
     *
     * @param string $path (Optional) The path
     * @return string
     */
    function site_url($path = '')
    {
        $uri = Config::get('URL');
        if (!$uri) {
            $uri = saika_detect_url();
        }
        // Remove begining trailing slash if exists
        $path = ltrim($path, '/');
        $uri .= $path;
        return $uri;
    }
}

if (!function_exists('load_helper')) {

    /**
     * Load user's custom defined functions aka helpers
     *
     * @param string $helper_file_name The helper file name ( without .php )
     * @return
     */
    function load_helper($helper_file_name)
    {
        require_once APP . 'helpers' . DIRECTORY_SEPARATOR . $helper_file_name . '.php';
    }
}

if (!function_exists('get_random_string')) {

    /**
     * Get a random string. Not secure! Just a random string!
     *
     * @param integer $length The length of the string
     * @param string  $keyspace  The characters to use, Default is: 0-9-A-z
     * @return string
     */
    function get_random_string($length = 10, $keyspace =
        '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTWXYZ')
    {
        $length = (int)$length;
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            if (function_exists('random_int')) {
                $k = random_int(0, $max);
            } elseif (function_exists('mt_rand')) {
                $k = mt_rand(0, $max);
            }
            else {
                $k = rand(0, $max);
            }

            $str .= $keyspace[$k];
        }
        return $str;
    }
}

if (!function_exists('get_secure_token')) {
    /**
     * Generate a cryptographically secure random token
     *
     * @param integer $length The length of token
     *                        ( may differ because it will be converted to hex )
     * @return string
     */
    function get_secure_token($length = 20)
    {
        // for PHP7 we have another great solution
        if (is_php('7')) {
            return bin2hex(random_bytes($length));
        }
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}


if (!function_exists('get_current_url')) {
    /**
     * Get current URL
     *
     * @return string
     */
    function get_current_url()
    {
        $page_url = '';

        if (is_https()) {
            $page_url .= 'https://';
        } else {
            $page_url .= 'http://';
        }
        if ($_SERVER['SERVER_PORT'] != '80') {
            $page_url .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
        } else {
            $page_url .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        }
        return $page_url;
    }
}


if (!function_exists('saika_url_slug')) {
    /**
     * Generate SEO friendly URL slug from string. With Unicode support.
     *
     * @param string $str     The string
     * @param array  $options The options as array, the option keys are:
     *                        delimiter : URL delimiter, default is "-"
     *                        limit : how long the slug could be? default is "null"
     *                        lowercase : whether to convert it to lowercase or not
     *                        replacements : custom replacements, as key => val array
     *                        transliterate : Transliterate characters to ASCII or not
     *
     * @return string
     */
    function saika_url_slug($str, array $options = array())
    {
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
            );

        // Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
        // Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'O' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'U' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'o' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'u' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
        // Latin symbols
            '©' => '(c)',
        // Greek
            '?' => 'A', '?' => 'B', 'G' => 'G', '?' => 'D', '?' => 'E', '?' => 'Z', '?' => 'H', 'T' => '8',
            '?' => 'I', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => '3', '?' => 'O', '?' => 'P',
            '?' => 'R', 'S' => 'S', '?' => 'T', '?' => 'Y', 'F' => 'F', '?' => 'X', '?' => 'PS', 'O' => 'W',
            '?' => 'A', '?' => 'E', '?' => 'I', '?' => 'O', '?' => 'Y', '?' => 'H', '?' => 'W', '?' => 'I',
            '?' => 'Y',
            'a' => 'a', 'ß' => 'b', '?' => 'g', 'd' => 'd', 'e' => 'e', '?' => 'z', '?' => 'h', '?' => '8',
            '?' => 'i', '?' => 'k', '?' => 'l', 'µ' => 'm', '?' => 'n', '?' => '3', '?' => 'o', 'p' => 'p',
            '?' => 'r', 's' => 's', 't' => 't', '?' => 'y', 'f' => 'f', '?' => 'x', '?' => 'ps', '?' => 'w',
            '?' => 'a', '?' => 'e', '?' => 'i', '?' => 'o', '?' => 'y', '?' => 'h', '?' => 'w', '?' => 's',
            '?' => 'i', '?' => 'y', '?' => 'y', '?' => 'i',
        // Turkish
            'S' => 'S', 'I' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'G' => 'G',
            's' => 's', 'i' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'g' => 'g',
        // Russian
            '?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Yo', '?' => 'Zh',
            '?' => 'Z', '?' => 'I', '?' => 'J', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
            '?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F', '?' => 'H', '?' => 'C',
            '?' => 'Ch', '?' => 'Sh', '?' => 'Sh', '?' => '', '?' => 'Y', '?' => '', '?' => 'E', '?' => 'Yu',
            '?' => 'Ya',
            '?' => 'a', '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo', '?' => 'zh',
            '?' => 'z', '?' => 'i', '?' => 'j', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => 'o',
            '?' => 'p', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c',
            '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => '', '?' => 'y', '?' => '', '?' => 'e', '?' => 'yu',
            '?' => 'ya',
        // Ukrainian
            '?' => 'Ye', '?' => 'I', '?' => 'Yi', '?' => 'G',
            '?' => 'ye', '?' => 'i', '?' => 'yi', '?' => 'g',
        // Czech
            'C' => 'C', 'D' => 'D', 'E' => 'E', 'N' => 'N', 'R' => 'R', 'Š' => 'S', 'T' => 'T', 'U' => 'U',
            'Ž' => 'Z',
            'c' => 'c', 'd' => 'd', 'e' => 'e', 'n' => 'n', 'r' => 'r', 'š' => 's', 't' => 't', 'u' => 'u',
            'ž' => 'z',
        // Polish
            'A' => 'A', 'C' => 'C', 'E' => 'e', 'L' => 'L', 'N' => 'N', 'Ó' => 'o', 'S' => 'S', 'Z' => 'Z',
            'Z' => 'Z',
            'a' => 'a', 'c' => 'c', 'e' => 'e', 'l' => 'l', 'n' => 'n', 'ó' => 'o', 's' => 's', 'z' => 'z',
            'z' => 'z',
        // Latvian
            'A' => 'A', 'C' => 'C', 'E' => 'E', 'G' => 'G', 'I' => 'i', 'K' => 'k', 'L' => 'L', 'N' => 'N',
            'Š' => 'S', 'U' => 'u', 'Ž' => 'Z',
            'a' => 'a', 'c' => 'c', 'e' => 'e', 'g' => 'g', 'i' => 'i', 'k' => 'k', 'l' => 'l', 'n' => 'n',
            'š' => 's', 'u' => 'u', 'ž' => 'z'
            );
        //Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        // Replace non-alphanumeric characters with our delimiter
        // Little modded by @mirazmac to support the full language structure
        $str = preg_replace("/[^\p{L}\p{Nd}\p{M}]+/u", $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);
        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }
}

if (!function_exists('is_https')) {
    /**
     * Is HTTPS?
     *
     * Determines if the application is accessed via an encrypted
     * (HTTPS) connection.
     *
     * @return  boolean
     */
    function is_https()
    {
        if (! empty($_SERVER['HTTPS']) && mb_strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && mb_strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
        } elseif (! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && mb_strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }

        return false;
    }
}

if (!function_exists('is_php')) {
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param   string
     * @return  bool    TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string) $version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}

if (!function_exists('saika_blue_screen')) {
    function saika_blue_screen($msg, $err_code = 'N/A')
    {
        $blue_screen = file_get_contents(SYSTEM . 'static/bsod.html');
        $blue_screen = str_replace(array('%%ERROR_CODE%%', '%%ERROR_MSG%%'),
            array($err_code, $msg), $blue_screen);
        echo $blue_screen;
        die(1);
    }
}

if (!function_exists('is_dev')) {
    /**
     * If current ENVIRONMENT is set to development or not
     *
     * @return boolean
     */
    function is_dev()
    {
        return ENVIRONMENT === 'dev' || ENVIRONMENT === 'development';
    }
}
