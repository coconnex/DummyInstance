<?php

namespace Coconnex\API\Base\Models\Contact\Collection;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/Collections/CNXCollection.Collection.php");
require_once(dirname(dirname(__FILE__)) . "/Models/ContactModel.Class.php");

use Coconnex\API\Base\Collections\CNXCollection;
use Coconnex\API\Base\Models\Contact\Models\ContactModel;
use Exception;

class Contacts extends CNXCollection
{
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof ContactModel)) {
            throw new \Exception("Contacts collection expects value to be ContactsModel but has receieved " . \get_class($value));
        }
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }
}
