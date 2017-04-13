<?php

/**
* Saika - The PHP Framework for KIDS
*
* The Filter Class
*
* @version 1.0
* @since 1.0
*/

class Filter
{

    /**
     * Applies htmlspecialchars() with ENT_QUOTES and UTF-8 as default
     *
     * @param  string $input    The input to escape
     * @param  boolean $encoding Toggle double encode, defaults to TRUE
     * @return string
     */
    public static function spchars($input, $double_encode = true)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8', $double_encode);
    }

    /**
     * Applies htmlentities() with ENT_QUOTES | ENT_HTML5 and UTF-8 as default
     *
     * @param  string $input    The input to escape
     * @param  boolean $encoding Toggle double encode, defaults to TRUE
     * @return string
     */
    public static function entities($input, $double_encode = true)
    {
        return htmlentities($input, ENT_QUOTES | ENT_HTML5, 'UTF-8', $double_encode);
    }


    /**
     * Apply multiple filters to variable
     *
     * @param mixed $var       The variable
     * @param string $functions Pipe separated list of custom functions to apply
     *                          ex. strip_tags|stroupper|trim.
     * @return mixed
     */
    public function batch($var, $functions = null)
    {
        foreach (explode('|', $functions) as $func) {
            if (is_callable($func)) {
                $var = call_user_func($func, $var);
            } else {
                throw new \Exception('Unable to call function '.$func);
            }
        }
        return $var;
    }
}
