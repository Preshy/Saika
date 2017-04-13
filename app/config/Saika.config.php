<?php

/**
 * Saika - The PHP Framework For KIDS
 *
 * The Saika Config File
 *
 * @since 1.0
 */

$_config = array();

/**
 * Basic Configuration
 *
 * @var  string $_config['URL'] Full URL of your site. MUST end with a trailing
 *                             slash ( / ) Ex. https://yoursite.com/
 *                             Usually detects automagically! You can change it
 *                             manually, if you don't like wizards :(
 *
 * @var  string $_config['CHARSET'] Set the internal character set
 * @var  string|boolean $_config['TIMEZONE'] Set the server timezone. Set it to
 *                                           FALSE to use default server timezone.
 */
$_config['URL'] = saika_detect_url();
$_config['CHARSET'] = 'UTF-8';
$_config['TIMEZONE'] = 'Asia/Dhaka';

/**
 * Database Configuration
 *
 * @var string $_config['DB_TYPE'] Database type. Currently tested with
 *                                 mysql or sqlite.
 * @var string $_config['DB_HOST'] Database host.
 * @var string $_config['DB_NAME'] Database name (Exception: For SQLite, it
 *                                 should be absolute path to the database file)
 * @var string $_config['DB_USER'] Database username
 * @var string $_config['DB_PASS'] Database user password.
 * @var string $_config['DB_PORT'] Database Port, Usually 3306. Better check it
 *                                 from phpinfo()
 * @var string $_config['DB_CHARSET'] Database Charset, utf8 is highly recommended!
 */
$_config['DB_TYPE'] = 'mysql';
$_config['DB_HOST'] = '127.0.0.1';
$_config['DB_NAME'] = 'saika';
$_config['DB_USER'] = 'root';
$_config['DB_PASS'] = '';
$_config['DB_PORT'] = '3306';
$_config['DB_CHARSET'] = 'utf8';

/**
 * Controller Related Configuration
 *
 * @var string $_config['DEFAULT_CONTROLLER'] Name of the default controller
 * @var string $_config['DEFAULT_ACTION'] Name of the default action
 * @var string $_config['ERROR_CONTROLLER'] Name of the Error controller
 * @var boolean $_config['TRANSLATE_HYPHENS'] Translate hyphens in action names
 *                                            to underscore.
 *                                            TRUE is HIGHLY RECOMMENDED!
 */
$_config['DEFAULT_CONTROLLER'] = 'index';
$_config['DEFAULT_ACTION'] = 'index';
$_config['ERROR_CONTROLLER'] = 'error';
$_config['TRANSLATE_HYPHENS'] = true;

/**
* Configuration for Cookies. Including but not limited to Session cookies
*
* @var string $_config['COOKIE_PATH'] The path the cookie is valid on,
*                                     usually "/" to make it valid on the
*                                     whole domain.
* @var string $_config['COOKIE_DOMAIN'] The domain where the cookie is valid for.
*                                       Usually this does not work with
*                                       "localhost", ".localhost", "127.0.0.1"
*                                       or ".127.0.0.1". If so, leave it as
*                                       empty string, false or null. When using
*                                       real domains make sure you have a dot(.)
*                                       in front of the domain, like
*                                       ".mydomain.com".s
* @var boolean $_config['COOKIE_SECURE'] If the cookie will be transferred
*                                        through secured connection(SSL). It's
*                                        highly recommended to set it to TRUE if
*                                        you have SSL enabled.
* @var boolean $_config['COOKIE_HTTPONLY'] If set to true, Cookies can't be
*                                          accessed by JS - TRUE is Highly
*                                          recommended!
*
* @var integer $_config['COOKIE_LIFETIME'] Default lifetime of cookie. Used by
*                                          the Cookie class. 7 days is default
*
* @var integer $_config['SESSION_LIFETIME'] How long should a session cookie be
*                                           valid by seconds, 0 = till the
*                                           browser is closed.
*
* @var boolean|string $_config['SESSION_SAVE_PATH'] Specify where the sessions
*                                                   will be saved. Set FALSE to
*                                                   use default directory.
*/

$_config['COOKIE_PATH'] = '/';
$_config['COOKIE_DOMAIN'] = '';
$_config['COOKIE_SECURE'] = false;
$_config['COOKIE_HTTPONLY'] = true;
$_config['COOKIE_LIFETIME'] = time() + 86400 * 7;
$_config['SESSION_LIFETIME'] = 0;
$_config['SESSION_SAVE_PATH'] = SYSTEM. 'Sessions';

/**
 * Autoload related Config, Currently only supports helpers.
 * Helpers are located at app/Helpers/
 * All other classes and libraries are autoloaded via Composer.
 * @see http://getcomposer.org
 *
 * @var array $_config['AUTOLOAD_HELPERS'] Name of Helper files without
 *                                         extension(.php) as array
 *                                         Ex. ['DemoHelper', 'UrlHelpers']
 */
$_config['AUTOLOAD_HELPERS'] = array();

/**
 * Encryption Keys. You should change this to unique values.
 *
 * @var string $_config['ENCRYPTION_KEY'] Encryption key to use for encryption
 *                                        /decryption in Encryption class.
 * @var string $_config['HMAC_SALT'] HMAC salt to use for encryption/decryption
 *                                   in Encryption class.
 */
$_config['ENCRYPTION_KEY'] = '|lN2#E9]/^?Ste,{1x7n[4L@WE}`Bd';
$_config['HMAC_SALT'] = '#4tTIF?2MTR^Vr-:6tLS"{_%!*9186';

/**
 * Configuration for Captcha Class
 *
 * @var string $_config['CAPTCHA_FONT'] Path to the captcha font
 * @var integer $_config['CAPTCHA_WIDTH'] Width of the captcha
 * @var integer $_config['CAPTCHA_HEIGHT'] Height of the captcha
 * @var integer $_config['CAPTCHA_FONT_SIZE'] Captcha font size
 * @var integer $_config['CAPTCHA_LENGTH'] The captcha length
 * @var boolean $_config['CAPTCHA_CASE_SENSITIVE'] Whether the captcha is case
 *                                                 sensitive or not
 */
$_config['CAPTCHA_FONT'] = SYSTEM. 'static/fonts/Dink.ttf';
$_config['CAPTCHA_WIDTH'] = 150;
$_config['CAPTCHA_HEIGHT'] = 50;
$_config['CAPTCHA_FONT_SIZE'] = 28;
$_config['CAPTCHA_LENGTH'] = 5;
$_config['CAPTCHA_CASE_SENSITIVE'] = true;

/**
 * Misc. Confguration
 *
 * @var string $_config['CACHE_DIR'] Specify the cache location. Must end with a
 *                                   trailing slash
 * @var integer $_config['CSRF_TOKEN_LIFETIME'] The CSRF token expiration time
 *                                              in seconds. Usually the session
 *                                              expires when the browser is closed.
 *                                              But in case you have increased the
 *                                              session lifetime it will help.
 *                                              3600 = 1 Hour
 */
$_config['CACHE_DIR'] = SYSTEM. 'cache/';
$_config['CSRF_TOKEN_LIFETIME'] = 3600;

// Finally return the config
return $_config;
