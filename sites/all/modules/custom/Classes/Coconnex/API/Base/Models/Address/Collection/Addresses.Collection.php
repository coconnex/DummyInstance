<?php

namespace Coconnex\API\Base\Models\Address\Collection;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Collections/CNXCollection.Collection.php");
require_once(dirname(dirname(__FILE__)) . "/Models/AddressModel.Class.php");

use Coconnex\API\Base\Collections\CNXCollection;
use Coconnex\API\Base\Models\Address\Models\AddressModel;
use Exception;

class Addresses extends CNXCollection
{
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof AddressModel)) {
            throw new \Exception("Addresses collection expects value to be AddressModel but has receieved " . \get_class($value));
        }
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }
}
