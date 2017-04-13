<?php

/**
 * Saika - The PHP Framework for KIDS
 *
 * Class View, handles all the output.
 *
 * @version 1.0
 * @since 1.0
 */

class View
{
    /**
     * Absolute path to base directory of Views
     *
     * @var string
     */
    protected $__saika_view_dir;

    /**
     * The data assigned to the template.
     *
     * @var array
     */
    protected $__saika_data = array();

    /**
     * Current template file name
     *
     * @var string
     */
    protected $__saika_file_name;

    /**
     * Start the view engine!
     *
     * @param string $view_dir Allows you to define custom view directory
     *                         This is useful for device based view switching
     */
    public function __construct($view_dir = '')
    {
        $this->__saika_view_dir = APP . 'views' . DIRECTORY_SEPARATOR;

        if (!empty($view_dir)) {
            $this->__saika_view_dir = $view_dir;
        }
    }

    /**
     * Merge the assigned data
     *
     * @param array $data
     * @access private
     * @return
     */
    private function mergeData(array $data)
    {
        $this->__saika_data =  array_merge($this->__saika_data, $data);
    }

    /**
     * Assign a variable to current view
     *
     * @param string $key   The variable name
     * @param mixed $value The variable value
     * @return
     */
    public function assign($key, $value = '')
    {
        $this->__saika_data[$key] = $value;
    }

    /**
     * Assign multiple variables to view
     *
     * @param array $data Data as array in key => value format
     * @return
     */
    public function assignMulti(array $data = array())
    {
        $this->mergeData($data);
    }

    /**
     * Render a view
     *
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param array $data Data to be used in the view
     */
    public function render($filename, array $data = array())
    {
        $this->mergeData($data);
        $this->__saika_file_name = $filename;
        unset($data, $filename); // To prevent unexpected collisions & re-use

        // Extract the variables
        extract($this->__saika_data);
        // Load the template
        require $this->__saika_view_dir . $this->__saika_file_name . '.php';
    }

    /**
     * Converts characters to HTML entities
     *
     * @param string $str       The string to escape
     * @param string $functions Pipe separated list of custom functions to apply
     *                          ex. strip_tags|stroupper|trim
     * @return string
     */
    public function escape($str, $functions = null)
    {
        if ($functions) {
            $str = $this->filters($str, $functions);
        }
        return Filter::entities($str);
    }


    /**
     * Short-hand alias of self::escape();
     *
     * @param string $str       The string to escape
     * @param string $functions Pipe separated list of custom functions to apply
     *                          ex. strip_tags|stroupper|trim
     * @return string
     */
    public function e($str, $functions = null)
    {
        return $this->escape($str, $functions);
    }

    /**
     * Apply multiple filters to variable
     *
     * @param mixed $var       The variable
     * @param string $functions Pipe separated list of custom functions to apply
     *                          ex. strip_tags|stroupper|trim. Special is "escape",
     *                          which will run self::escape()
     * @return mixed
     */
    public function filters($var, $functions = null)
    {
        foreach (explode('|', $functions) as $func) {
            if ($func == 'escape') {
                $var = $this->escape($var);
                continue;
            }

            if (is_callable($func)) {
                $var = call_user_func($func, $var);
            } else {
                throw new \Exception('Unable to call function ' . $func);
            }
        }
        return $var;
    }

    /**
     * Short-hand alias of self::filters()
     *
     * @param mixed $var       The variable
     * @param string $functions Pipe separated list of custom functions to apply
     *                          ex. strip_tags|stroupper|trim. Special is "escape",
     *                          which will run self::escape()
     * @return mixed
     */
    public function f($var, $functions = null)
    {
        return $this->filters($var, $functions);
    }

    /**
     * Quick access the URI
     *
     * @param string $path (Optional) The path
     * @return string
     */
    public function uri($path = '')
    {
        return site_url($path);
    }
}
