<?php

namespace Coconnex\API\Base\Models\Address\Models;

require_once(dirname(dirname(__FILE__)) . "/Traits/Address.Trait.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/Country/Models/CountryModel.Class.php");
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/AbstractClasses/Model.Abstract.php");

use API\Base\Models\Address\Traits\Address;
use Coconnex\API\Base\AbstractClasses\Model;
use Coconnex\API\Base\Models\Country\Models\CountryModel;

class AddressModel extends Model
{
    use Address;

    public function init()
    {
        $this->country = new CountryModel();
    }
}
