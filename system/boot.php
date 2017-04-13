<?php

/**
 * Saika - The PHP Framework For KIDS
 *
 * @author Miraz Mac <mirazmac@gmail.com>
 * @version 1.0
 * @since 1.0
 */

// Load core functions first
require_once SYSTEM . 'core/Functions.php';

// Make sure the PHP version is at-least 5.4
if(!is_php('5.4')) {
    saika_blue_screen('You need at-least PHP 5.4 to run Saika. Your current PHP
     version is '. PHP_VERSION);
}

/**
 * Set the error reporting according to the environment
 *
 * Forked from Codeigniter 3.1.2
 */
switch (@strtolower(ENVIRONMENT)) {
    case 'development':
    case 'dev':
    error_reporting(-1);
    ini_set('display_errors', 1);
    break;

    case 'testing':
    case 'production':
    ini_set('display_errors', 0);
    if (version_compare(PHP_VERSION, '5.3', '>=')) {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
    } else {
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
    }
    break;

    default:
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    saika_blue_screen('The application environment is not set correctly.<br/>
        Please define <code>ENVIRONMENT</code> constant in index.php');
    }

/**
 * Load composer dependencies
 *
 * !! Important: Don't forget to run "composer install" !!
 */
require ROOT . 'vendor/autoload.php';

/**
 * Autoload custom helpers
 */
$helpers = Config::get('AUTOLOAD_HELPERS');
if (is_array($helpers) && !empty($helpers)) {
    foreach ($helpers as $helper) {
        load_helper($helper);
    }
}

$charset = mb_strtoupper(Config::get('CHARSET'));
// Set Internal charset
// We are suppressing errors because in few servers ini_set() is disabled and
// generates warning
@ini_set('default_charset', $charset);
@ini_set('mbstring.internal_encoding', $charset);
@ini_set('iconv.internal_encoding', $charset);

// Unset the variables to avoid future re-use or conflicts
unset($charset, $helpers, $helper);

// Set server timezone
if (Config::get('TIMEZONE')) {
    date_default_timezone_set(Config::get('TIMEZONE'));
}

/**
 * Change the session save path
 */
if (Config::get('SESSION_SAVE_PATH')) {
    session_save_path(Config::get('SESSION_SAVE_PATH'));
}
/**
 * Change session cookie parameters
 */
session_set_cookie_params((int)Config::get('SESSION_LIFETIME'), Config::get('COOKIE_PATH'), Config::get('COOKIE_DOMAIN'),
    (bool)Config::get('COOKIE_SECURE'), (bool)Config::get('COOKIE_HTTPONLY'));

/**
 * Secure headers
 *
 * Bring my violin Watson!
 */

// Enable strict transport security, if connection is SSL
if (is_https()) {
    header('Strict-Transport-Security: max-age=16070400; includeSubDomains');
}

// Disable framing, except same site
header('X-Frame-Options: sameorigin');

// Enable Browser level XSS protection
header('X-XSS-Protection: 1; mode=block');

// Disable sniffing
header('X-Content-Type-Options: nosniff');

// Remove PHP version from header
header_remove('x-powered-by');

/**
 * Start the application
 *
 * Boom!
 */
new App();
