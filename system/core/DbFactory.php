<?php

/**
 * Saika - The PHP Framework for KIDS
 *
 * DbFactory Class, Forked from Huge
 *
 * Use it like this:
 * $db = DbFactory::getFactory()->getConn();
 */
class DbFactory
{
    /** @var object Database Factory Instance */
    private static $factory;

    /** @var object The PDO instance */
    private $database;

    /**
     * Class constructor
     *
     * Prevent creating duplicate instance by declaring it as private
     * @access private
     */
    private function __construct()
    {
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }

    /**
     * Get the database factory
     *
     * @return object
     */
    public static function getFactory()
    {
        if (!self::$factory) {
            self::$factory = new DbFactory();
        }
        return self::$factory;
    }

    /**
     * Get PDO database connection instance
     *
     * @param array $conn_opts Optional. Providing connection details here will
     *                         override database details defined in config file.
     * @return object
     */
    public function getConn(array $conn_opts = array())
    {
        if (!$this->database) {
            $defaults = array(
                'DB_TYPE' => Config::get('DB_TYPE'),
                'DB_HOST' => Config::get('DB_HOST'),
                'DB_NAME' => Config::get('DB_NAME'),
                'DB_PORT' => Config::get('DB_PORT'),
                'DB_CHARSET' => Config::get('DB_CHARSET'),
                'DB_USER' => Config::get('DB_USER'),
                'DB_PASS' => Config::get('DB_PASS'),
                );
            $conn_opts = array_merge($defaults, $conn_opts);
            $sqlite_types = array('sqlite', 'sqlite2');
            // Since SQLite is different and doesn't require User crendetials
            // We will treat him better!
            $sqlite = false;
            if (in_array(mb_strtolower($conn_opts['DB_TYPE']), $sqlite_types)) {
                $dsn = $conn_opts['DB_TYPE'] . ':' . $conn_opts['DB_NAME'];
                $sqlite = true;
            } else {
                $dsn = $conn_opts['DB_TYPE'] . ':host=' . $conn_opts['DB_HOST'] .
                 ';dbname=' . $conn_opts['DB_NAME'] . ';port=' . $conn_opts['DB_PORT'] . ';charset='
                 . $conn_opts['DB_CHARSET'];
            }


            /**
             * Check DB connection in try/catch block. Also when PDO is not constructed properly,
             * prevent to exposing database host, username and password in plain text as:
             * PDO->__construct('mysql:host=127....', 'root', '12345678', Array)
             * by throwing custom error message
             */
            try {
                $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                 PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
                if ($sqlite) {
                    $this->database = new PDO($dsn, '', '', $options);
                } else {
                    $this->database = new PDO($dsn, $conn_opts['DB_USER'],
                     $conn_opts['DB_PASS'], $options);
                }
            } catch (PDOException $e) {

                // Echo custom message. Echo error code gives you some info.
                echo 'Database connection can not be estabilished. Please try again later.' . '<br>';
                echo 'Error code: ' . $e->getCode();

                // Stop application :(
                // No connection, reached limit connections etc. so no point to keep it running
                exit;
            }
        }
        return $this->database;
    }
}
