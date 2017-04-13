<?php

/**
 * Saika - The PHP Framework For KIDS
 *
 * The Main Application Class. Routes all request to their controller
 *
 * @version 1.0
 * @since 1.0
 */
class App
{
    /** @var mixed Instance of the controller */
    private $controller;

    /** @var string The Path to Controller Directory */
    private $controller_dir;

    /** @var string Current controller file */
    private $controller_file;

    /** @var string Name of the Error Controller */
    private $error_controller;

    /** @var array URL parameters, will be passed to used controller-method */
    private $parameters = array();

    /** @var string Just the name of the controller */
    private $controller_name;

    /** @var string Just the name of the controller's method */
    private $action_name;

    /**
     * Start the application, analyze URL elements, call according controller/method
     * or relocate to fallback location
     */
    public function __construct()
    {
        $this->controller_dir = APP . 'controllers' . DIRECTORY_SEPARATOR;
        $this->error_controller = ucfirst(Config::get('ERROR_CONTROLLER')) . 'Controller';

        // create array with URL parts in $url
        $this->splitUrl();

        // creates controller and action names (from URL input)
        $this->boot();

        $this->controller_file = $this->controller_dir . $this->controller_name . '.php';

        // does such a controller exist ?
        if (file_exists($this->controller_file)) {

            // load this file and create this controller
            require $this->controller_file;
            $this->controller = new $this->controller_name();

            if (Config::get('TRANSLATE_HYPHENS')) {

            // If the action name contains underscores replace it with hypens
            // And make a 301 (permanent) redirect
            // Why we are doing this?
            // Because search engines hate duplicate content and underscore in URL
                if (mb_strpos($this->action_name, '_') && Request::get('url')) {
                    $url_parts = explode('/', trim(Request::get('url'), '/'));
                    $url_parts[1] = str_replace('_', '-', $url_parts[1]);
                    $redir = implode('/', $url_parts);
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: ' . Config::get('URL') . ' ' . $redir);
                    exit(1);
                }
                // Translate hypens in method aka action name to underscore
                $this->action_name = str_replace('-', '_', $this->action_name);
            }

            // To make sure its callable
            $is_callable = is_callable(array($this->controller, $this->action_name));

            // To make sure its not a magic method
            $is_magic_method = (mb_strpos($this->action_name, '__') === 0) ? true : false;

            // Make sure:
            // the method is callable ( eg. not a private method, the method exists )
            // the method is not a magic method
            if ($is_callable && !$is_magic_method) {
                $reflect = new ReflectionMethod($this->controller, $this->action_name);
                $required_params = $reflect->getNumberOfRequiredParameters();
                $action_params = count($this->parameters);

                if (!empty($this->parameters)) {

                    // We are doing this because:
                    // We don't want to show the PHP error message to visitors
                    // And if required params aka url attributes are missing,
                    // its better to show an error page instead of nasty php errors!
                    if ($action_params < $required_params && !is_dev()) {
                        $this->loadErrorController();
                    } else {
                        // call the method and pass arguments to it
                        call_user_func_array(array($this->controller, $this->action_name), $this->parameters);
                    }
                } else {
                    // We are doing this because:
                    // We don't want to show the PHP error message to visitors
                    // And if required arguments aka url attributes are missing,
                    // its better to show an error page instead of nasty php errors!
                    if ($action_params < $required_params && !is_dev()) {
                        $this->loadErrorController();
                    } else {
                        $this->controller->{$this->action_name}();
                    }
                }
                // We're too humble!
                unset($reflect);
            } else {
                // load the error controller
                $this->loadErrorController();
            }
        } else {
            // load the error controller
            $this->loadErrorController();
        }
    }

    /**
     * Get and split the URL
     */
    private function splitUrl()
    {
        if (Request::get('url')) {

            // split URL
            $url = trim(Request::get('url'), '/');
            $url = explode('/', $url);

            // put URL parts into according properties
            $this->controller_name = isset($url[0]) ? $url[0] : null;
            $this->action_name = isset($url[1]) ? $url[1] : null;

            // remove controller name and action name from the split URL
            unset($url[0], $url[1]);

            // rebase array keys and store the URL parameters
            $this->parameters = array_values($url);
        }
    }

    /**
     * Checks if controller and action names are given. If not, default values are put into the properties.
     * Also renames controller to usable name.
     */
    private function boot()
    {
        // check for controller: no controller given ? then make controller = default controller (from config)
        if (!$this->controller_name) {
            $this->controller_name = Config::get('DEFAULT_CONTROLLER');
        }

        // check for action: no action given ? then make action = default action (from config)
        if (!$this->action_name || (mb_strlen($this->action_name) == 0)) {
            $this->action_name = Config::get('DEFAULT_ACTION');
        }

        // rename controller name to real controller class/file name ("index" to "IndexController")
        $this->controller_name = ucwords($this->controller_name) . 'Controller';
    }

    private function loadErrorController()
    {
        // load the error controller
        require $this->controller_dir . $this->error_controller . '.php';
        $this->controller = new $this->error_controller();
        $this->controller->{Config::get('DEFAULT_ACTION')}();
    }
}
