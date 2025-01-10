<?php

namespace Coconnex\API\Base\AbstractClasses;

require_once(dirname(dirname(__FILE__)) . "/Interfaces/I_Model.Interface.php");

use Coconnex\API\Base\Collections\CNXCollection;
use Coconnex\API\Base\Interfaces\I_Model;

abstract class Model implements I_Model
{
    public function __construct()
    {
        $this->init();
    }

    public function copy($source, &$target = null)
    {
        if (!$target) $target = &$this;
        foreach ($source as $prop => $value) {
            if (is_object($value)) {
                $this->copy($value, $target->{$prop});
            } else {
                if (property_exists($target, $prop)) {
                    $target->{$prop} = $value;
                }
            }
        }
    }
}
