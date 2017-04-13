<?php

/**
* Saika - The PHP Framework for KIDS
*
* The Redirect Wrapper Class
*
* @version 1.0
* @since 1.0
*/

class Redirect
{


    /**
     * Redirect to a path within the site ( eg. index/test )
     *
     * @param  string  $path   The Path
     * @param  boolean $force Toggle force redirect, requires output buffering
     * @return
     */
    public static function to($path, $force = true)
    {
        $url = Config::get('URL');
        $url .= ltrim($path, '/');

        // Flush buffer data to make a redirect
        // when force redirect is enabled
        if ($force && ob_get_level()):
            ob_end_clean();
        endif;

        header('Location: '.$url);
        exit();
    }

    /**
     * Redirect to a absolute URL ( eg. http://google.com/bla+bla )
     *
     * @param  string  $url   The URL
     * @param  boolean $force Toggle force redirect, requires output buffering
     * @return
     */
    public static function absto($url, $force = true)
    {
        $url = trim($url);

        // Flush buffer data to make a redirect
        // when force redirect is enabled
        if ($force && ob_get_level()):
            ob_end_clean();
        endif;

        header('Location: '.$url);
        exit();
    }


    /**
     * Redirect to a path in client side way ( eg. index/test )
     *
     * @param  string  $path   The Path
     * @param  integer $after  Time in seconds to wait before redirecting
     * @return
     */
    public static function htmlRedir($path, $after = 0)
    {
        $url = Config::get('URL');
        $url .= ltrim($path, '/');
        $after = (int)$after;
        ?>
        <script type="text/javascript">
        setTimeout(function(){window.location.href="<?=$url?>"}, <?=$after?>);
        </script>
        <noscript>
        <meta http-equiv="refresh" content="<?=$after?>; url=<?=$url?>">
        </noscript>
        <?php
    }

    /**
     * Redirect to a a absolute URL in client side way ( eg. http://google.com/bla+bla )
     *
     * @param  string  $path   The URL
     * @param  integer $after  Time in seconds to wait before redirecting
     * @return
     */
    public static function abshtmlRedir($url, $after = 0)
    {
        $url = trim($url);
        $after = (int)$after;
        ?>
        <script type="text/javascript">
        setTimeout(function(){window.location.href="<?=$url?>"}, <?=$after?>);
        </script>
        <noscript>
        <meta http-equiv="refresh" content="<?=$after?>; url=<?=$url?>">
        </noscript>
        <?php
    }

    /**
     * Redirect back to previous page. Makes use of $_SERVER['HTTP_REFERER'],
     * If $_SERVER['HTTP_REFERER'] is not set/empty it will redirect to home page
     *
     * @param  boolean $ignore_other_hosts If you set this to TRUE then it will only
     *                                     redirect back to inner site URL.
     * @param  boolean $force              Toggle force redirect, requires output buffering
     * @return
     */
    public static function back($ignore_other_hosts = false, $force = true)
    {
        // Flush buffer data to make a redirect
        // when force redirect is enabled
        if ($force && ob_get_level()):
            ob_end_clean();
        endif;

        if (empty($_SERVER['HTTP_REFERER'])) {
            header('Location: '.Config::get('URL'));
            exit();
        }

        // Should we only go back within the site?
        if ($ignore_other_hosts) {
            $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            $saika_host = parse_url(Config::get('url'), PHP_URL_HOST);

            if ($saika_host != $referer_host) {
                header('Location: '.Config::get('URL'));
                exit();
            }
        }

        // Just get back to your origin -_-
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit();

    }
}
