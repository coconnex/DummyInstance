<?php

namespace Coconnex\API\Base\Models;

class CallBack
{
    public $url;
    public $class;
    public $function;

    public function __construct($url = "", $class = "", $function = "")
    {
        if (trim($url)) $this->url = $url;
        if (trim($class)) $this->class = $class;
        if (trim($function)) $this->function = $function;
    }
}
