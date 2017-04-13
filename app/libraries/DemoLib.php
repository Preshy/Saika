<?php

/**
* Demo Lib Class
*
* Please note how the class name and class file name is same
*/
class DemoLib
{
    public $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    public function showName()
    {
        echo $this->name;
    }
}
