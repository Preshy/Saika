<?php

/**
 * Saika - The PHP Framework For KIDS
 *
 * @author Miraz Mac <mirazmac@gmail.com>
 * @version 1.0
 * @since 1.0
 *
 * @todo Add namespace support
 * @todo Implement tests
 * @todo Add more detailed code comments to improve readability
 */

//=======================================================================//
//                  This project is based on huge,                       //
//      A Simple user-authentication solution, embedded                  //
//                      into a small framework.                          //
//                                                                       //
//              @see HUGE-LICENSE for more information                   //
//=======================================================================//

/**
 * Application Environment
 *
 * Accepted values: production, testing and development
 */
define('ENVIRONMENT', 'development');

/**
 * The framework version
 */
define('SAIKA_VERSION', '1.0');

/**
 * Absolute Path to the framework root, with directory separator at end
 */
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

/**
 * Absolute Path to the System Directory, with directory separator at end
 */
define('SYSTEM', ROOT . 'system' . DIRECTORY_SEPARATOR);

/**
 * Absolute Path to the Application Directory, with directory separator at end
 */
define('APP', ROOT . 'app' . DIRECTORY_SEPARATOR);

/**
 * Boot the system watson!
 *
 * Well, that was fast!
 */
require SYSTEM . 'boot.php';

