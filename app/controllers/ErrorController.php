<?php

/**
 * Error Controller
 *
 * Handles error pages.
 *
 */

class ErrorController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * We are reserving the index method for 404 error page
     *
     * @return
     */
    public function index()
    {
        header('HTTP/1.0 404 Not Found', true, 404);
        echo '404 Not Found';
    }
}
